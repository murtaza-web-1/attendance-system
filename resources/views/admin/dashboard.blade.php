@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>

    {{-- ✅ Flash Message --}}
    @if(session('success'))
        <div style="color:green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif


    {{-- 📊 Attendance Summary --}}
    <div style="margin: 20px 0; padding: 15px; background: #f9f9f9; border: 1px solid #ccc;">
        <h4>📊 Attendance Summary</h4>
        <ul style="list-style: none; padding-left: 0;">
            <li><strong>✅ Present:</strong> {{ $presentCount }}</li>
            <li><strong>🚫 Absent:</strong> {{ $absentCount }}</li>
            <li><strong>🛑 Leave:</strong> {{ $leaveCount }}</li>
        </ul>
    </div>
    {{-- ✅ Attendance Table --}}

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead style="background-color: #f0f0f0;">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Date</th>
            <th>Status</th>
            <th>Leave Approved</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($attendances as $a)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $a->user->name ?? 'N/A' }}</td>
            <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
            <td>
                @if($a->status === 'present')
                    ✅ Present
                @elseif($a->status === 'absent')
                    🚫 Absent
                @elseif($a->status === 'leave')
                    🛑 Leave
                @else
                    ❓ {{ ucfirst($a->status) }}
                @endif
            </td>
            <td>{{ $a->leave_approved ? '✅ Approved' : '❌ Not Approved' }}</td>
            <td>
                <a href="{{ route('admin.attendance.edit', $a->id) }}">✏️ Edit</a> |

                <form method="POST" action="{{ route('admin.attendance.delete', $a->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure to delete this record?')">🗑️ Delete</button>
                </form> |

                <form method="POST" action="{{ route('admin.attendance.toggle-leave', $a->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit">
                        {{ $a->leave_approved ? '❎ Unapprove' : '✅ Approve' }} Leave
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center;">No attendance records found.</td>
        </tr>
    @endforelse
    </tbody>
</table>

</div>
@endsection
