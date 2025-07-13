@extends('layouts.admin')

@section('content')
@include('layouts.navbar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 15px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .badge-status {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 14px;
        }

        .btn-icon {
            width: 100%;
            margin-bottom: 8px;
        }
    }
</style>

<div class="container mt-4">

    {{-- ✅ Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <h3 class="mb-3">Welcome to Admin Dashboard</h3>

            @if (auth('web')->user()->hasRole('Admin'))
                <p class="text-success fw-semibold">You are an Admin ✅</p>
            @else
                <p class="text-danger fw-semibold">You are NOT an Admin ❌</p>
            @endif

            {{-- 📊 Attendance Summary --}}
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-success bg-opacity-10">
                        <div class="card-body">
                            <h5 class="text-success">✅ Present</h5>
                            <p class="fs-4">{{ $presentCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-danger bg-opacity-10">
                        <div class="card-body">
                            <h5 class="text-danger">🚫 Absent</h5>
                            <p class="fs-4">{{ $absentCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning bg-opacity-10">
                        <div class="card-body">
                            <h5 class="text-warning">🛑 Leave</h5>
                            <p class="fs-4">{{ $leaveCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ Attendance Table --}}
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover align-middle bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Leave Approval</th>
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
                                    @if($a->status === 'Present')
                                        <span class="badge bg-success badge-status">✅ Present</span>
                                    @elseif($a->status === 'Absent')
                                        <span class="badge bg-danger badge-status">🚫 Absent</span>
                                    @elseif($a->status === 'Leave')
                                        <span class="badge bg-warning text-dark badge-status">🛑 Leave</span>
                                    @else
                                        <span class="badge bg-secondary badge-status">❓ {{ ucfirst($a->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($a->status === 'Leave')
                                        {!! $a->leave_approved
                                            ? '<span class="badge bg-success">✅ Approved</span>'
                                            : '<span class="badge bg-danger">❌ Not Approved</span>' !!}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.attendance.edit', $a->id) }}" class="btn btn-sm btn-primary btn-icon">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('admin.attendance.delete', $a->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure to delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger btn-icon" type="submit">
                                            🗑️ Delete
                                        </button>
                                    </form>

                                    @if($a->status === 'Leave')
                                        <form method="POST" action="{{ route('admin.attendance.toggle-leave', $a->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-sm {{ $a->leave_approved ? 'btn-warning text-dark' : 'btn-success' }} btn-icon" type="submit">
                                                {{ $a->leave_approved ? '❎ Unapprove' : '✅ Approve' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
