<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $attendances = \App\Models\Attendance::with('user')->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $leaveCount = $attendances->where('status', 'leave')->count();

        return view('admin.dashboard', compact(
            'attendances',
            'presentCount',
            'absentCount',
            'leaveCount'
        ));
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $students = User::all(); // Assuming you want to pass students
        return view('admin.attendances.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->only(['status', 'date']));
        return redirect()->route('admin.dashboard')->with('success', 'Attendance updated.');
    }

    public function destroy($id)
    {
        Attendance::destroy($id);
        return redirect()->route('admin.dashboard')->with('success', 'Attendance deleted.');
    }

    public function toggleLeave($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->leave_approved = !$attendance->leave_approved;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave status updated.');
    }

    public function leaves()
    {
        // Example: Show all leave requests
        $leaves = Attendance::where('status', 'leave')->get();
        return view('admin.leaves', compact('leaves'));
    }

    public function approveLeave($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->leave_approved = true;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave approved.');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $attendances = Attendance::whereBetween('date', [$request->from, $request->to])->get();
        return view('admin.reports', compact('attendances'));
    }

    public function grading()
    {
        // Example grading logic (you can customize)
        $users = User::with('attendances')->get();
        return view('admin.grading', compact('users'));
    }

    public function saveGrading(Request $request)
    {
        // You can define logic here to save custom grading thresholds
        // Example placeholder
        // config(['grades.A' => 36, 'B' => 30]); or store in DB
    }
}
