{{-- 📄 Partial View for AJAX --}}
<h2 class="mb-4">Your Attendance Records</h2>

{{-- 🔍 Filter Form --}}
<form method="GET" class="row g-3 align-items-end mb-4">
    <div class="col-md-3">
        <label>Status</label>
        <select name="status" class="form-select">
            <option value="">-- All --</option>
            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
    </div>

    <div class="col-md-3">
        <label>End Date</label>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
    </div>

    <div class="col-md-3 d-flex">
        <button class="btn btn-primary w-100 me-2">🔍 Filter</button>
        <a href="{{ route('attendance.export', request()->query()) }}" class="btn btn-success">⬇️ Export</a>
    </div>
</form>

{{-- 📄 Table --}}
@if($records->isEmpty())
    <div class="alert alert-info">No attendance records found.</div>
@else
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
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
                            <span class="badge bg-success">Present</span>
                        @elseif($record->status === 'absent')
                            <span class="badge bg-danger">Absent</span>
                        @elseif($record->status === 'leave')
                            <span class="badge bg-warning text-dark">Leave</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 📑 Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $records->appends(request()->query())->links() }}
    </div>
@endif
