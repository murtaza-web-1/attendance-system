<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Task;
use Spatie\Permission\Models\Role;
class AdminController extends Controller
{
    /**
     * Admin dashboard: show all attendance records with summary.
     */
    public function dashboard()
    {
        $attendances = Attendance::with('user')->latest()->get();

        $presentCount = $attendances->where('status', 'Present')->count();
        $absentCount  = $attendances->where('status', 'Absent')->count();
        $leaveCount   = $attendances->where('status', 'Leave')->count();

        return view('admin.dashboard', compact(
            'attendances',
            'presentCount',
            'absentCount',
            'leaveCount'
        ));
    }

    /**
     * Filter and manage attendance by date or name.
     */
    public function manageAttendance(Request $request)
    {
        $query = Attendance::with('user')->orderBy('date', 'desc');

        // Filter by date range
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }

        // Search by user name
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $attendances = $query->get();

        $presentCount = $attendances->where('status', 'Present')->count();
        $absentCount  = $attendances->where('status', 'Absent')->count();
        $leaveCount   = $attendances->where('status', 'Leave')->count();

        return view('admin.dashboard', compact(
            'attendances',
            'presentCount',
            'absentCount',
            'leaveCount'
        ));
    }

    /**
     * Show edit form for a single attendance record.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $students = User::all();

        return view('admin.edit', compact('attendance', 'students'));
    }

    /**
     * Update attendance status/date.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent,Leave',
            'date'   => 'required|date',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->only(['status', 'date']));

        return redirect()->route('admin.dashboard')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Delete an attendance record.
     */
    public function destroy($id)
    {
        Attendance::destroy($id);
        return redirect()->route('admin.dashboard')->with('success', 'Attendance record deleted.');
    }

    /**
     * Toggle leave approval for a leave record.
     */
    public function toggleLeave($id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->status !== 'leave') {
            return redirect()->back()->with('error', 'Only leave records can be approved or unapproved.');
        }

        $attendance->leave_approved = !$attendance->leave_approved;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave approval status updated.');
    }

    /**
     * Show all leave requests.
     */
    public function leaves()
    {
        $leaves = Attendance::where('status', 'leave')->with('user')->get();
        return view('admin.leaves', compact('leaves'));
    }

    /**
     * Approve a single leave request (force approve).
     */
    public function approveLeave($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->leave_approved = true;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Leave approved successfully.');
    }

    /**
     * Show date filter form for reports.
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Generate attendance report for a date range.
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
     * Display students with attendance counts and assign grades.
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
     * Save grading data to the users table.
     */
    public function saveGrading(Request $request)
    {
        foreach ($request->grades as $userId => $grade) {
            User::where('id', $userId)->update(['grade' => $grade]);
        }

        return redirect()->route('admin.grading')->with('success', 'Grades saved successfully.');
    }

    /**
     * Show form to create a new task.
     */
    public function createTaskForm()
    {
    return view('admin.create-task');
    }

    public function storeTask(Request $request)
    {
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ]);

    Task::create([
        'title' => $request->title,
        'description' => $request->description, // Will contain HTML from CKEditor
        'user_id' => $request->user_id,
    ]);

    return redirect()->route('admin.createTask')->with('success', 'Task created successfully!');
    }

    public function assignRole(Request $request)
    {
        $request->validate([
        'user_id' => 'required|exists:users,id',
        'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles([$request->role]); // remove old and assign new

        return response()->json([
        'message' => "Role '{$request->role}' assigned to user successfully.",
        'user' => $user
        ]);
    }
}
