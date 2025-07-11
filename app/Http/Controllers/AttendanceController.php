<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // ✅ Used in Blade views (web)
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

        return redirect()->back()->with('success', 'Attendance marked successfully!');
    }

    // ✅ Used in Blade views (web)
    public function markLeave(Request $request)
    {
        $user = auth('web')->user(); // ✅ Explicitly using 'web' guard
        $today = Carbon::today();

        $alreadyMarked = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->exists();

        if ($alreadyMarked) {
            return redirect()->back()->with('success', 'You have already marked attendance/leave today');
        }

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'leave',
            'date' => $today
        ]);

        return redirect()->back()->with('success', 'Leave marked successfully!');
    }

    // ✅ For Blade views (web)
    public function viewAttendance(Request $request)
    {
        $user = auth('web')->user(); // ✅ Explicitly using 'web' guard

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status']);

        return view('attendance.view', ['records' => $attendances]);
    }

    // ✅ For API (Postman, Mobile App, etc.)
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
}
