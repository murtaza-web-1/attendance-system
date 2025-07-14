<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-width: 240px;
            background-color: #0d47a1;
            color: #fff;
        }

        .sidebar a {
            color: #ddd;
            display: block;
            padding: 14px 20px;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #1565c0;
            color: #ffffff;
        }

        .sidebar .user-info {
            padding: 20px;
            background: #0b3c91;
            border-bottom: 1px solid #1565c0;
        }

        .sidebar .user-info strong {
            font-size: 16px;
        }

        .sidebar .user-info small {
            color: #cfd8dc;
        }

        .sidebar-toggle {
            display: none;
        }

        .flex-grow-1 {
            background: #ffffff;
            padding: 30px;
            flex-grow: 1;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            box-shadow: -3px 0 20px rgba(0,0,0,0.08);
        }

        form button {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                z-index: 999;
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                background: #0d47a1;
                color: white;
                border: none;
                padding: 10px 12px;
                z-index: 1000;
                border-radius: 5px;
            }

            .flex-grow-1 {
                padding: 20px;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

{{-- ☰ Toggle Button --}}
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('show')">
    <i class="bi bi-list"></i>
</button>

{{-- 🌐 Sidebar --}}
<div class="sidebar">
    <div class="user-info">
        <strong>{{ auth()->user()->name }}</strong><br>
        <small>{{ auth()->user()->getRoleNames()->implode(', ') }}</small>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="ajax-link">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    @hasrole('Admin')
        <a href="{{ route('admin.roles.index') }}" class="ajax-link">
            <i class="bi bi-person-badge"></i> Manage Roles
        </a>

        <a href="{{ route('admin.permissions.index') }}" class="ajax-link">
            <i class="bi bi-shield-lock"></i> Manage Permissions
        </a>

        <a href="{{ route('admin.createTask') }}" class="ajax-link">
            <i class="bi bi-pencil-square"></i> Assign Tasks
        </a>
    

    @endhasrole

    @hasanyrole('Admin|Teacher|HR')
        <a href="{{ route('admin.attendance.view') }}" class="ajax-link">
            <i class="bi bi-clipboard-check"></i> Attendance Panel
        </a>

        <a href="{{ route('admin.reports') }}" class="ajax-link">
            <i class="bi bi-graph-up"></i> Attendance Reports
        </a>

        <a href="{{ route('admin.grading') }}" class="ajax-link">
            <i class="bi bi-award"></i> Grading
        </a>
    @endhasanyrole

    <form action="{{ route('admin.logout') }}" method="POST" style="padding: 10px 20px;">
        @csrf
        <button class="btn btn-sm btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>


{{-- 📦 Main Content Area --}}
<div id="main-content">
    @yield('content')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{--  AJAX Sidebar Navigation --}}
<script>
    //  Highlight the correct sidebar link on page load
    $(document).ready(function () {
        const currentUrl = window.location.href;

        $('.ajax-link').each(function () {
            if (this.href === currentUrl) {
                $(this).addClass('active');
            }
        });
    });
    $(document).on('click', '.ajax-link', function (e) {
        e.preventDefault();

        const $clickedLink = $(this);
        const url = $clickedLink.attr('href');

        // Remove active class from all
        $('.ajax-link').removeClass('active');

        // Add active to clicked link
        $clickedLink.addClass('active');

        // Optional loader
        $('#main-content').html(`
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        // Load via AJAX
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                $('#main-content').html(response);
                history.pushState(null, '', url);
            },
            error: function () {
                $('#main-content').html('<div class="alert alert-danger">Failed to load page.</div>');
            }
        });
    });

    // Handle browser back/forward
    window.addEventListener('popstate', function () {
        const currentUrl = window.location.href;

        // Update active class
        $('.ajax-link').removeClass('active');
        $('.ajax-link').each(function () {
            if (this.href === currentUrl) {
                $(this).addClass('active');
            }
        });

        $.get(currentUrl, function (response) {
            $('#main-content').html(response);
        });
    });
</script>




@yield('scripts')
</body>
</html>
