@extends('layouts.app')

@section('content')
<h2>Manage Role Permissions</h2>

@foreach($roles as $role)
    <form method="POST" action="{{ route('admin.permissions.update', $role->id) }}">
        @csrf
        <h4>{{ $role->name }}</h4>
        @foreach($permissions as $permission)
            <label>
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                {{ $permission->name }}
            </label>
        @endforeach
        <button type="submit">Update</button>
    </form>
@endforeach
@endsection
