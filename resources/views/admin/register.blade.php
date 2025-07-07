@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            {{-- <h3 class="card-title text-center mb-4">Admin Registration</h3> --}}

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
<div class="text-center mb-3 bold-text font-weight-bold">
    <span class="badge bg-primary">Registering as Admin</span>
</div>

            <form action="{{ route('admin.register.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <input type="hidden" name="role" value="admin">

                <button type="submit" class="btn btn-primary w-100">Register as Admin</button>
            </form>

            <p class="text-center mt-3">
                Already have an account?
                <a href="{{ route('admin.login') }}">Login as Admin</a>
            </p>
        </div>
    </div>
</div>
@endsection
