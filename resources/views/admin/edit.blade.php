@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Attendance</h2>

    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
        @csrf

        <label>Date:</label>
        <input type="date" name="date" value="{{ $attendance->date }}" required><br><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="leave" {{ $attendance->status == 'leave' ? 'selected' : '' }}>Leave</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</div>
@endsection
