@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4 text-center">🛡️ Manage User Roles</h3>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Assign New Role</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach ($users as $user)
<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
        <span class="badge bg-primary">
            {{ $user->getRoleNames()->first() ?? 'None' }}
        </span>
    </td>
    <td>
        <form method="POST" action="{{ route('admin.roles.assign', $user->id) }}" class="role-assign-form">
            @csrf
            <div class="d-flex gap-2">
                <select name="role" class="form-select form-select-sm" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-success">Update</button>
            </div>
            <div class="role-message mt-2"></div>
        </form>
    </td>
</tr>
@endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('submit', '.role-assign-form', function (e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr('action');
        const formData = form.serialize();
        const messageBox = form.find('.role-message');

        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: formData,
            success: function(response) {
                messageBox.html('<div class="alert alert-success p-2 mb-0">' + response.message + '</div>');
            },
            error: function(xhr) {
                let errorText = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorText = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                messageBox.html('<div class="alert alert-danger p-2 mb-0">' + errorText + '</div>');
            }
        });
    });
</script>
@endsection

@endsection
