@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Assigned Tasks</h2>

    @if($tasks->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Admin Feedback</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{!! $task->description !!}</td>
                        <td>
                            <span class="badge bg-{{ $task->status === 'submitted' ? 'success' : 'warning' }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td>{{ $task->admin_feedback ?? '—' }}</td>
                    </tr>

                    @if ($task->status === 'pending')
                    <tr>
                        <td colspan="4">
                            <form action="{{ route('user.tasks.submit', $task->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="response_{{ $task->id }}" class="form-label">Your Response</label>
                                    <textarea class="form-control" name="response" id="response_{{ $task->id }}" rows="3" required>{{ old('response') }}</textarea>
                                </div>

                                @if ($errors->any())
                                    <div class="text-danger mb-2">
                                        @foreach ($errors->all() as $error)
                                            <div>• {{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary">Submit Task</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            No tasks assigned yet.
        </div>
    @endif
    <a class="logout-btn" href="{{ route('logout') }}">Logout</a>
</div>
@endsection




<script>
    $(document).ready(function () {
        $('#view-attendance-btn').click(function () {
            $('#attendance-section').html('<p>Loading attendance...</p>');

            $.ajax({
                url: "{{ route('attendance.view.submit') }}",
                method: "GET",
                success: function (data) {
                    let html = `
                        <h4>Your Attendance</h4>
                        <table>
                            <thead>
                                <tr><th>Date</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        html += '<tr><td colspan="2">No records found.</td></tr>';
                    } else {
                        data.forEach(record => {
                            html += `<tr><td>${record.date}</td><td>${record.status}</td></tr>`;
                        });
                    }

                    html += '</tbody></table>';
                    $('#attendance-section').html(html);
                },
                error: function () {
                    $('#attendance-section').html('<p style="color:red;">Error loading attendance data.</p>');
                }
            });
            $.ajax({
                url: "{{ route('leave.mark') }}",
                method: "GET",
                success: function (data) {
                    let html = `
                        <h4>Your Leave Records</h4>
                        <table>
                            <thead>
                                <tr><th>Date</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                    `;

                    if (data.length === 0) {
                        html += '<tr><td colspan="2">No records found.</td></tr>';
                    } else {
                        data.forEach(record => {
                            html += `<tr><td>${record.date}</td><td>${record.status}</td></tr>`;
                        });
                    }

                    html += '</tbody></table>';
                    $('#attendance-section').append(html);
                },
                error: function () {
                    $('#attendance-section').append('<p style="color:red;">Error loading leave data.</p>');
                }
            });
          $.ajax({
    url: "{{ route('user.tasks.list') }}", 
    method: "GET",                         
    success: function (data) {
        let html = `
            <h4>Your Tasks</h4>
            <table>
                <thead>
                    <tr><th>Title</th><th>Description</th><th>Status</th></tr>
                </thead>
                <tbody>
        `;

        if (data.length === 0) {
            html += '<tr><td colspan="3">No tasks found.</td></tr>';
        } else {
            data.forEach(task => {
                html += `<tr><td>${task.title}</td><td>${task.description}</td><td>${task.status}</td></tr>`;
            });
        }

        html += '</tbody></table>';
        $('#attendance-section').append(html);
    },
    error: function () {
        $('#attendance-section').append('<p style="color:red;">Error loading task data.</p>');
    }
});

        });
    });
</script>

</body>
</html>
