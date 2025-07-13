<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 1rem;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 2rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('admin.roles.index') }}">🛡 Manage Roles</a>
        <a href="{{ route('admin.permissions.index') }}">🔐 Manage Permissions</a>
        <a href="{{ route('admin.createTask') }}">📋 Assign Tasks</a>
        <a href="{{ route('admin.attendance.view') }}">📅 Attendance</a>
        <a href="{{ route('admin.grading') }}">🎓 Grading</a>
        <a href="{{ route('admin.leaves') }}">📩 Leave Requests</a>
        <a href="{{ route('admin.reports') }}">📊 Reports</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-danger mt-3 w-100">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
