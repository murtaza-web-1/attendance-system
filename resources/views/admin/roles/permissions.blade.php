@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .permission-card {
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0,0,0,0.05);
    }

    .badge-permission {
        background-color: #0d6efd;
        color: white;
        margin-right: 5px;
        margin-top: 4px;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .badge-permission {
            display: block;
            width: 100%;
        }
    }
</style>

<div class="container my-4">
    <div class="card permission-card p-4 mb-5">
        <h3 class="mb-4">🔐 Manage Role Permissions</h3>

        {{-- ✅ Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Assign Permission Form --}}
        <form action="{{ route('admin.permissions.assign') }}" method="POST" class="reload-form" >
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Select Role</label>
                    <select name="role" class="form-select" required>
                        <option disabled selected>Choose a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Select Permission</label>
                    <select name="permission" class="form-select" required>
                        <option disabled selected>Choose a permission...</option>
                        @foreach($permissions as $perm)
                            <option value="{{ $perm->name }}">{{ $perm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success w-100">
                        ✅ Assign Permission
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- 🔍 Roles and their Permissions --}}
    <div class="card permission-card p-4">
        <h5 class="mb-3">🧾 Roles and Their Permissions</h5>

     <ul class="list-group list-group-flush">
    @foreach($roles as $role)
        <li class="list-group-item">
            <strong>{{ ucfirst($role->name) }}</strong>:
            @if($role->permissions->count())
                <div class="mt-2 d-flex flex-wrap gap-2">
                    @foreach($role->permissions as $perm)
                        <span class="badge badge-permission d-flex align-items-center">
                            {{ $perm->name }}
                            <button class="btn btn-sm btn-danger ms-2 btn-remove-permission"
                                    data-role="{{ $role->name }}"
                                    data-permission="{{ $perm->name }}"
                                    title="Remove Permission">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </span>
                    @endforeach
                </div>
            @else
                <span class="text-muted">No permissions yet</span>
            @endif
        </li>
    @endforeach
</ul>

    </div>
</div>
@section('scripts')
<script>
    $(document).on('click', '.btn-remove-permission', function () {
        const role = $(this).data('role');
        const permission = $(this).data('permission');

        // if (!confirm(`Are you sure to remove "${permission}" from ${role}?`)) return;                

        $.ajax({
            url: "{{ route('admin.permissions.remove') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                role: role,
                permission: permission
            },
            success: function () {
                $.get("{{ route('admin.permissions.index') }}", function (response) {
                    $('#main-content').html(response);
                    history.pushState(null, '', "{{ route('admin.permissions.index') }}");
                });
            },
            error: function () {
                alert("Failed to remove permission.");
            }
        });
    });
</script>
@endsection


@endsection
