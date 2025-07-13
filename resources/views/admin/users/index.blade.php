@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Users</h2>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead style="background-color: #f0f0f0;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
