<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function markAttendance(Request $request)
    {
        $user = $request->user();
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

    public function markLeave(Request $request)
    {
        $user = $request->user();
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

 public function viewAttendance(Request $request)
{
    $user = $request->user();

    $attendances = Attendance::where('user_id', $user->id)
        ->orderBy('date', 'desc')
        ->get(['date', 'status']);

    // Return to the Blade view named 'attendance.view'
    return view('attendance.view', ['records' => $attendances]);
}
public function getAttendanceData(Request $request)
{
    $user = Auth::user();

    $attendances = Attendance::where('user_id', $user->id)
        ->orderBy('date', 'desc')
        ->get(['date', 'status']);

    return response()->json($attendances);
}

    
}
