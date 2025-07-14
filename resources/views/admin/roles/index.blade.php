@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manage User Roles</h3>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Current Role</th>
                <th>Assign New Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }} ({{ $user->email }})</td>
                <td>{{ $user->getRoleNames()->first() ?? 'None' }}</td>
              <td>
    <form action="{{ route('admin.roles.assign', $user) }}" method="POST" class="role-assign-form">
        @csrf
        <div class="d-flex gap-2">
            <select name="role" class="form-select form-select-sm" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-success">Update</button>
        </div>
    </form>
    <div class="role-message mt-2"></div>
</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).on('submit', '.role-assign-form', function (e) {
    e.preventDefault();
     console.log("🔁 Submitting Role Assign Form...");

    const form = $(this);
    const actionUrl = form.attr('action');
    const data = form.serialize();
    const messageBox = form.closest('td').find('.role-message');

    $.post(actionUrl, data)
        .done(function(response) {
            messageBox.html(`<div class="alert alert-success p-2 mb-0">${response.message}</div>`);
        })
        .fail(function(xhr) {
            let errorText = '❌ Something went wrong.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                errorText = errors;
            }

            messageBox.html(`<div class="alert alert-danger p-2 mb-0">${errorText}</div>`);
        });
});
</script>
@endsection
