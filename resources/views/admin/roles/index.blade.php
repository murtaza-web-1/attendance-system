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
                    <form action="{{ route('admin.roles.assign', $user) }}" method="POST">
                        @csrf
                        <select name="role" class="form-select d-inline w-auto">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary">Assign</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
