<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 36px 32px 28px 32px;
        }
        h2 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        .dummy-data {
            margin: 24px 0;
            padding: 18px;
            background: #f1f8e9;
            border-radius: 8px;
            border: 1px solid #c5e1a5;
        }
        .dummy-data ul {
            margin: 0;
            padding-left: 18px;
        }
        .logout-btn {
            display: inline-block;
            padding: 8px 18px;
            background: #d32f2f;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            text-decoration: none;
            margin-top: 18px;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #b71c1c;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #cfcfcf;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eeeeee;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <div class="dummy-data">

            {{-- Flash Message --}}
            @if(session('success'))
                <div style="color: green; font-weight: bold; margin-bottom: 10px;">
                    {{ session('success') }}
                </div>
            @endif

            <h4>Dashboard</h4>
            <ul>
                <li>Last login: {{ now()->subDays(1)->toDateTimeString() }}</li>
                <li>Role: User</li>
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

            <div id="attendance-section" style="margin-top: 20px;"></div>

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
                            <h3>Your Attendance</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        if (data.length === 0) {
                            html += '<tr><td colspan="2">No records found.</td></tr>';
                        } else {
                            data.forEach(function (record) {
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
