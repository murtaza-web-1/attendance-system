<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Services\WhatsAppService;
use App\Events\AttendanceMarked;
use App\Events\LeaveRequested;

use Carbon\Carbon;

class AttendanceController extends Controller
{
    //  Used in Blade views (web)


public function markAttendance(Request $request)
{
    $user = auth('web')->user(); // ✅ Explicitly using 'web' guard
    $today = Carbon::today();

    $alreadyMarked = Attendance::where('user_id', $user->id)
        ->where('date', $today)
        ->exists();

    if ($alreadyMarked) {
        return redirect()->back()->with('success', 'You have already marked attendance today');
    }

    Attendance::create([
        'user_id' => $user->id,
        'status' => 'present',
        'date' => $today
    ]);

    // ✅ Trigger WhatsApp Event
    event(new AttendanceMarked($user));

    return redirect()->back()->with('success', 'Attendance marked successfully!');
}

    //  Used in Blade views (web)


public function markLeave(Request $request)
{
    $request->validate([
        'reason' => 'required|string',
        'date' => 'nullable|date', // optional date support
    ]);

    $user = auth('web')->user();
    $date = $request->date ?? Carbon::today();

    $alreadyMarked = Attendance::where('user_id', $user->id)
        ->where('date', $date)
        ->exists();

    if ($alreadyMarked) {
        return redirect()->back()->with('success', 'You have already marked attendance/leave for this date');
    }

    Attendance::create([
        'user_id' => $user->id,
        'status' => 'leave',
        'date' => $date,
        'reason' => $request->reason,
    ]);

    // ✅ Trigger WhatsApp Event
    event(new LeaveRequested($user, $request->reason, $date->toDateString()));

    return redirect()->back()->with('success', 'Leave marked successfully!');
}
    //  For Blade views (web)
    public function viewAttendance(Request $request)
    {
        $user = auth('web')->user(); // ✅ Explicitly using 'web' guard

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status']);

        return view('attendance.view', ['records' => $attendances]);
    }

    // For API (Postman, Mobile App, etc.)
    public function getAttendanceData(Request $request)
    {
        $user = auth('api')->user(); // ✅ Explicitly using 'api' guard

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status']);

        return response()->json($attendances);
    }
//  For Admin Dashboard (Blade views)
    public function index(Request $request)
    {
    $status = $request->input('status');
    $start  = $request->input('start_date');
    $end    = $request->input('end_date');

    $query = Attendance::with('user');

    if ($status) {
        $query->where('status', $status);
    }

    if ($start && $end) {
        $query->whereBetween('date', [$start, $end]);
    }

    $attendances = $query->orderBy('date', 'desc')->paginate(10);

    // Counts
    $presentCount = Attendance::where('status', 'Present')->count();
    $absentCount  = Attendance::where('status', 'Absent')->count();
    $leaveCount   = Attendance::where('status', 'Leave')->count();

    return view('admin.attendance.index', compact(
        'attendances', 'presentCount', 'absentCount', 'leaveCount'
    ));
    }
    // For user-side filtering in Blade view
public function userAttendance(Request $request)
{
    $query = Attendance::where('user_id', auth('web')->id());

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->start_date) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    $records = $query->orderBy('date', 'desc')->paginate(10);

    return view('user.attendance.index', compact('records'));
}



}
