<?php

namespace App\Http\Controllers;

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
             return back()->with(['success', 'You have already marked attendance today'], 403);
            // return response()->json(['message' => 'You have already marked attendance today'], 403);
        }

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'present',
            'date' => $today
        ]);
        //   return back()->withErrors(['email' => 'Invalid credentials']);
        //    return redirect('/login')->with('success', 'Registration successful. Please login.');
     return back()->with(['success', 'Attendance marked successfully']);
    }

    public function markLeave(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        $alreadyMarked = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->exists();

        if ($alreadyMarked) {
            return response()->json(['message' => 'You have already marked attendance/leave today'], 403);
        }

        Attendance::create([
            'user_id' => $user->id,
            'status' => 'leave',
            'date' => $today
        ]);

        return response()->json(['message' => 'Leave marked']);
    }

    public function viewAttendance(Request $request)
    {
        $user = $request->user();

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get(['date', 'status']);

        return response()->json($attendances);
    }
}
