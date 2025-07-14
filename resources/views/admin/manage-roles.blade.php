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
                        <form method="POST" action="{{ route('admin.roles.assign', $user->id) }}">
                            @csrf
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $user->getRoleNames()->first() ?? 'None' }}
                                </span>
                            </td>
                            <td>
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
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
