
<div class="dashboard-content">
    <h2>Attendance Summary</h2>
    <ul>
        <li><strong>Present:</strong> {{ $presentCount }}</li>
        <li><strong>Absent:</strong> {{ $absentCount }}</li>
        <li><strong>Leave:</strong> {{ $leaveCount }}</li>
    </ul>

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
