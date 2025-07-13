<nav style="background-color: #1565c0; padding: 10px;">
    <ul style="display: flex; list-style: none; gap: 20px; color: white; margin: 0; padding: 0;">
        {{-- <li><a href="{{ route('/') }}" style="color: white; text-decoration: none;">Home</a></li> --}}

        @if(auth()->check() && auth()->user()->hasRole('Admin'))
            <li><a href="{{ route('admin.roles.index') }}" style="color: white; text-decoration: none;">Manage Roles</a></li>
            <li><a href="{{ route('admin.users.index') }}" style="color: white; text-decoration: none;">Manage Users</a></li>
        @endif

        @if(auth()->check() && auth()->user()->hasRole('Teacher'))
            <li><a href="{{ route('teacher.tasks') }}" style="color: white; text-decoration: none;">View Tasks</a></li>
        @endif

        @if(auth()->check() && auth()->user()->hasRole('Student'))
            <li><a href="{{ route('dashboard') }}" style="color: white; text-decoration: none;">My Dashboard</a></li>
        @endif
    </ul>
</nav>
