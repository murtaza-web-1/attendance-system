@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>

    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    <div style="margin-bottom: 20px;">
        <h3>📊 Attendance Summary</h3>
        <ul>
            <li>✅ Present: {{ $presentCount }}</li>
            <li>🚫 Absent: {{ $absentCount }}</li>
            <li>🛑 Leave: {{ $leaveCount }}</li>
        </ul>
    </div>

    <table border="1" cellpadding="10">
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Date</th>
            <th>Status</th>
            <th>Leave Approved</th>
            <th>Actions</th>
        </tr>
        @foreach($attendances as $a)
        <tr>
            <td>{{ $a->id }}</td>
            <td>{{ $a->user->name }}</td>
            <td>{{ $a->date }}</td>
            <td>{{ $a->status }}</td>
            <td>{{ $a->leave_approved ? '✅ Approved' : '❌ Not Approved' }}</td>
            <td>
                <a href="{{ route('admin.attendance.edit', $a->id) }}">✏️ Edit</a> |
                <form method="POST" action="{{ route('admin.attendance.delete', $a->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this record?')">❌ Delete</button>
                </form> |
                <form method="POST" action="{{ route('admin.attendance.toggle-leave', $a->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit">Toggle Leave</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
