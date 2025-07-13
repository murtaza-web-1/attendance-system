<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .dashboard-card {
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0,0,0,0.06);
    }

    .summary-stat {
        font-size: 1.1rem;
    }

    .badge-status {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
    }

    @media (max-width: 768px) {
        .summary-stat {
            font-size: 1rem;
        }

        .badge-status {
            display: inline-block;
            margin-top: 5px;
        }
    }
</style>

<div class="container mt-4">
    <div class="card dashboard-card p-4 mb-4">
        <h3 class="mb-3">📊 Attendance Summary</h3>

        <div class="row text-center mb-3">
            <div class="col-md-4 mb-3">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body">
                        <h5 class="text-success">✅ Present</h5>
                        <p class="summary-stat">{{ $presentCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body">
                        <h5 class="text-danger">🚫 Absent</h5>
                        <p class="summary-stat">{{ $absentCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning bg-opacity-10">
                    <div class="card-body">
                        <h5 class="text-warning">🛑 Leave</h5>
                        <p class="summary-stat">{{ $leaveCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3">📅 Attendance Records</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
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
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                            <td>
                                @if($attendance->status === 'Present')
                                    <span class="badge bg-success badge-status">✅ Present</span>
                                @elseif($attendance->status === 'Absent')
                                    <span class="badge bg-danger badge-status">🚫 Absent</span>
                                @elseif($attendance->status === 'Leave')
                                    <span class="badge bg-warning text-dark badge-status">🛑 Leave</span>
                                @else
                                    <span class="badge bg-secondary badge-status">{{ $attendance->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @if($attendances->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center text-muted">No attendance records found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
