<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e3f2fd;
        }

        .dashboard-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 32px;
        }

        h2 { color: #0d47a1; font-size: 26px; margin-bottom: 12px; }
        h4 { margin-top: 24px; font-size: 20px; color: #333; }

        .dummy-data {
            background: #f9fbe7;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #dce775;
            margin-top: 15px;
        }

        .dummy-data ul { list-style: none; padding-left: 0; }
        .dummy-data li { margin-bottom: 12px; font-size: 15px; }

        button, .logout-btn {
            padding: 10px 16px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button { background-color: #1976d2; color: #fff; }
        button:hover { background-color: #0d47a1; }
        .logout-btn {
            background-color: #d32f2f;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .logout-btn:hover { background-color: #b71c1c; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th { background-color: #eeeeee; }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        @media (max-width: 600px) {
            .dashboard-container { margin: 20px; padding: 20px; }
            h2 { font-size: 22px; }
            button, .logout-btn { width: 100%; margin-bottom: 10px; }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, {{ Auth::user()->name }}</h2>

    @if(session('success'))
        <div style="color: green; font-weight: bold; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="dummy-data">
        <h4>Dashboard</h4>
        <ul>
            <li><strong>Last login:</strong> {{ now()->subDays(1)->toDateTimeString() }}</li>
            <li><strong>Role:</strong> User</li>
            <li>
                <form method="POST" action="{{ route('attendance.mark') }}">
                    @csrf
                    <button type="submit">Mark Attendance</button>
                </form>
            </li>
            <li>
                <form method="POST" action="{{ route('leave.mark') }}">
                    @csrf
                    <button type="submit">Mark Leave</button>
                </form>
            </li>
            <li>
                <button type="button" id="view-attendance-btn">View Attendance</button>
            </li>
        </ul>

        <div id="attendance-section"></div>

        <h4>📋 My Assigned Tasks</h4>

        @if($tasks->isEmpty())
            <div style="padding: 10px; background: #fff3cd; border-radius: 6px;">
                You have no tasks assigned yet.
            </div>
        @else
            <table>
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
                                <span style="color: rgb(0, 85, 255); padding: 4px 8px; border-radius: 4px;"
                                      class="badge bg-{{ $task->status === 'submitted' ? 'success' : 'warning' }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>{{ $task->admin_feedback ?? '—' }}</td>
                        </tr>

                        @if($task->status === 'pending')
                        <tr>
                            <td colspan="4">
                                <form action="{{ route('task.submit', $task->id) }}" method="POST">
                                    @csrf
                                    <textarea name="response" placeholder="Write your response..." required></textarea>
                                    <button type="submit" style="margin-top: 10px;">Submit Task</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <a class="logout-btn" href="{{ route('logout') }}">Logout</a>
</div>

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
        });
    });
</script>

</body>
</html>
