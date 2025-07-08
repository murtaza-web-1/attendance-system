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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <div class="dummy-data">
             @if (session('success'))
            <div class="success-message">
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
                    <a href="{{ route('attendance.view') }}">
                        <button type="button">View Attendance</button>
                    </a>
                </li>
            </ul>
        </div>
        <a class="logout-btn" href="{{ route('logout') }}">Logout</a>
    </div>
</body>

</html>
