<div class="d-flex justify-content-center my-4">
    <div class="card p-4 shadow-sm border rounded" style="background-color: #f9fbfd; max-width: 1000px; width: 100%;">
        <h3 class="mb-4 text-center">📆 Your Attendance Records</h3>

        {{-- 🔍 Filter Form --}}
        <form method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- All --</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100">
                    🔍 Filter
                </button>
                <a href="{{ route('attendance.export', request()->query()) }}" class="btn btn-success w-100">
                    ⬇️ Export
                </a>
            </div>
        </form>

        {{-- 📄 Attendance Table --}}
        @if($records->isEmpty())
            <div class="alert alert-info text-center">No attendance records found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                                <td>
                                    @if($record->status === 'present')
                                        <span class="badge bg-success">✅ Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="badge bg-danger">🚫 Absent</span>
                                    @elseif($record->status === 'leave')
                                        <span class="badge bg-warning text-dark">🛑 Leave</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 📑 Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $records->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
