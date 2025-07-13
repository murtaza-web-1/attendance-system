@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Attendance</h2>

    <form method="POST" action="{{ route('admin.attendance.update', $attendance->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Date:</label>
            <input type="date" name="date" value="{{ $attendance->date }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label>Status:</label>
            <select name="status" required>
                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="leave" {{ $attendance->status == 'leave' ? 'selected' : '' }}>Leave</option>
            </select>
        </div>

        <button type="submit" style="margin-top: 15px;">Update Attendance</button>
    </form>

    <a href="{{ route('admin.dashboard') }}" style="margin-top: 15px; display:inline-block;">⬅ Back to Dashboard</a>
</div>
@endsection
