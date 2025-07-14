<div class="container mt-5">
    <h3><i class="bi bi-cloud-arrow-up-fill"></i> Submitted Tasks</h3>

    {{-- ✅ Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ No submissions --}}
    @if($submissions->isEmpty())
        <div class="alert alert-info mt-3">No task submissions found.</div>
    @else
    {{-- ✅ Table of Submissions --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>User</th>
                    <th>Task</th>
                    <th>Response</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->user->name }}</td>
                        <td>{{ $submission->task->title }}</td>
                        <td>{{ $submission->response }}</td>
                        <td>
                            <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </td>
                        <td>{{ $submission->admin_feedback ?? '—' }}</td>
                        <td>
                            @if($submission->status === 'pending')
                                <form action="{{ route('admin.submissions.update', $submission->id) }}" method="POST">
                                    @csrf
                                    <div class="d-flex flex-column gap-1">
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="approved">Approve</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                        <input type="text" name="admin_feedback" class="form-control form-control-sm" placeholder="Optional feedback">
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </div>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
