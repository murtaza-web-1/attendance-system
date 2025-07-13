@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manage Role Permissions</h3>
    <form action="{{ route('admin.permissions.assign') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col">
                <select name="role" class="form-select">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <select name="permission" class="form-select">
                    @foreach($permissions as $perm)
                        <option value="{{ $perm->name }}">{{ $perm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <button class="btn btn-success">Assign Permission</button>
            </div>
        </div>
    </form>

    <h5>Roles and their Permissions</h5>
    <ul class="list-group">
        @foreach($roles as $role)
            <li class="list-group-item">
                <strong>{{ $role->name }}:</strong>
                {{ $role->permissions->pluck('name')->join(', ') ?: 'No permissions yet' }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
