<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin dashboard: general overview.
     */
    public function dashboard()
    {
        $attendances = Attendance::with('user')->latest()->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $leaveCount   = $attendances->where('status', 'leave')->count();

        return view('admin.dashboard', compact(
            'attendances',
            'presentCount',
            'absentCount',
            'leaveCount'
        ));
    }

    /**
     * Manage attendance with filters and search.
     */
  public function manageAttendance(Request $request)
{
    $query = Attendance::with('user')->orderBy('date', 'desc');

    // 🔍 Filter by date range
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('date', [$request->from, $request->to]);
    }

    // 🔍 Filter by user name
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    $attendances = $query->get();

    // 📊 Count attendance types
    $presentCount = $attendances->where('status', 'present')->count();
    $absentCount  = $attendances->where('status', 'absent')->count();
    $leaveCount   = $attendances->where('status', 'leave')->count();

    // ✅ Return to the existing dashboard view
    return view('admin.dashboard', compact(
        'attendances',
        'presentCount',
        'absentCount',
        'leaveCount'
    ));
}


    /**
     * Show edit form for attendance.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $students = User::all();

        return view('admin.attendances.edit', compact('attendance', 'students'));
    }

    /**
     * Update attendance record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,leave',
            'date'   => 'required|date',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->only(['status', 'date']));

        return redirect()->route('admin.dashboard')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Delete attendance record.
     */
    public function destroy($id)
    {
        Attendance::destroy($id);
        return redirect()->route('admin.dashboard')->with('success', 'Attendance record deleted.');
    }

    /**
     * Toggle leave approval status.
     */
    public function toggleLeave($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->leave_approved = !$attendance->leave_approved;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave approval status updated.');
    }

    /**
     * List all leave requests.
     */
    public function leaves()
    {
        $leaves = Attendance::where('status', 'leave')->with('user')->get();
        return view('admin.leaves', compact('leaves'));
    }

    /**
     * Approve a leave.
     */
    public function approveLeave($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->leave_approved = true;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave approved successfully.');
    }

    /**
     * Show report filter form.
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Generate report based on date range.
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $attendances = Attendance::with('user')
            ->whereBetween('date', [$request->from, $request->to])
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.reports', compact('attendances'));
    }

    /**
     * Grade students based on attendance.
     */
    public function grading()
    {
        $users = User::with(['attendances' => function ($query) {
            $query->where('status', 'present');
        }])->get();

        foreach ($users as $user) {
            $count = $user->attendances->count();

            if ($count >= 36) {
                $user->grade = 'A';
            } elseif ($count >= 30) {
                $user->grade = 'B';
            } elseif ($count >= 25) {
                $user->grade = 'C';
            } elseif ($count >= 15) {
                $user->grade = 'D';
            } else {
                $user->grade = 'F';
            }
        }

        return view('admin.grading', compact('users'));
    }

    /**
     * Save grading logic if needed.
     */
    public function saveGrading(Request $request)
    {
        // Optional: Save grade logic to DB if you have a `grade` column in users table
    foreach ($request->grades as $userId => $grade) {
        User::where('id', $userId)->update(['grade' => $grade]);
    }

    return redirect()->route('admin.grading')->with('success', 'Grades saved successfully.');
    }
}
