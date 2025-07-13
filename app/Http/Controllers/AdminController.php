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
    public function dashboard(Request $request)
{
    // Fetch data
    $attendances = Attendance::with('user')->latest()->get();

    $presentCount = $attendances->where('status', 'Present')->count();
    $absentCount  = $attendances->where('status', 'Absent')->count();
    $leaveCount   = $attendances->where('status', 'Leave')->count();

    $data = compact('attendances', 'presentCount', 'absentCount', 'leaveCount');

    // Check if it's an AJAX request
    if ($request->ajax()) {
        // Return only the content view for AJAX
        return view('admin.users.content-dashboard', $data);
    }

    // Else return the full Blade view (with layout)
    return view('admin.users.dashboard', $data);
}

    /**
     * Show all users with their roles.
     */
    public function users()
    {
    $users = \App\Models\User::with('roles')->get();
    return view('admin.users.index', compact('users'));
    }
    /**
     * Filter and manage attendance by date or name.
     */
public function manageAttendance(Request $request)
{
    $query = Attendance::query();

    // Optional filters
    if ($request->has('status') && $request->status !== '') {
        $query->where('status', $request->status);
    }

    if ($request->filled('start_date')) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    $records = $query->latest()->paginate(10);

    // AJAX vs Full Page
    if ($request->ajax()) {
        // return view('attendance.partial', compact('records'));
        return view('admin.attendance.partial', compact('records'));
    }

    // return view('attendance.index', compact('records'));
    return view('admin.attendance.view', compact('records'));
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
