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

    {{-- ✅ Create Role --}}
    <div class="card permission-card p-4 mb-4">
        <h4>➕ Create a New Role</h4>
        <div id="role-alert" class="my-2"></div>
        <form id="create-role-form">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control" placeholder="e.g. Professor" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">Create Role</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ✅ Assign Permission --}}
    <div class="card permission-card p-4 mb-4">
        <h4>✅ Assign Permission to Role</h4>
        <form id="assign-permission-form">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="role" class="form-select" required>
                        <option disabled selected>Choose a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="permission" class="form-select" required>
                        <option disabled selected>Choose a permission...</option>
                        @foreach($permissions as $perm)
                            <option value="{{ $perm->name }}">{{ $perm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">Assign Permission</button>
                </div>
                <strong>{{ ucfirst($role->name) }}</strong>
@if($role->name !== 'Admin')
    <button class="btn btn-sm btn-outline-danger ms-2 btn-delete-role"
            data-role="{{ $role->name }}"
            title="Delete Role">
        🗑️
    </button>
@endif

            </div>
        </form>
        <div id="assign-alert" class="mt-3"></div>
    </div>

    {{-- 🔍 View All Roles & Their Permissions --}}
    <div class="card permission-card p-4">
        <h4>🧾 Roles and Their Permissions</h4>
        <ul class="list-group list-group-flush mt-3">
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
@endsection

@section('scripts')
<script>
    // ✅ Remove Permission
    $(document).on('click', '.btn-remove-permission', function () {
        const role = $(this).data('role');
        const permission = $(this).data('permission');

        $.ajax({
            url: "{{ route('admin.permissions.remove') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                role: role,
                permission: permission
            },
            success: function () {
                $.get("{{ route('admin.permissions.index') }}", function (res) {
                    $('#main-content').html(res);
                    history.pushState(null, '', "{{ route('admin.permissions.index') }}");
                });
            },
            error: function () {
                alert("Failed to remove permission.");
            }
        });
    });

    // ✅ Assign Permission
    $(document).on('submit', '#assign-permission-form', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.permissions.assign') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                $('#assign-alert').html(`<div class="alert alert-success">${res.message}</div>`).fadeIn().delay(3000).fadeOut();
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong!';
                $('#assign-alert').html(`<div class="alert alert-danger">❌ ${msg}</div>`).fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // ✅ Create Role
    $(document).on('submit', '#create-role-form', function (e) {
        e.preventDefault();
        const btn = $(this).find('button');
        btn.prop('disabled', true).text('Creating...');

        $.ajax({
            url: "{{ route('admin.roles.store.ajax') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                $('#role-alert').html(`<div class="alert alert-success">${res.message}</div>`).fadeIn().delay(3000).fadeOut();
                $('#create-role-form')[0].reset();
                $('select[name="role"]').append(`<option value="${res.role}">${res.role}</option>`);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong!';
                $('#role-alert').html(`<div class="alert alert-danger">❌ ${msg}</div>`).fadeIn().delay(3000).fadeOut();
            },
            complete: function () {
                btn.prop('disabled', false).text('Create Role');
            }
        });
    });
    $(document).on('click', '.btn-delete-role', function () {
    const role = $(this).data('role');
    // if (!confirm(`Are you sure you want to delete the "${role}" role?`)) return;

    $.ajax({
        url: "{{ route('admin.roles.delete') }}",
        method: "DELETE",
        data: {
            _token: '{{ csrf_token() }}',
            role: role
        },
        success: function (res) {
            alert(res.message);
            $.get("{{ route('admin.permissions.index') }}", function (html) {
                $('#main-content').html(html);
                history.pushState(null, '', "{{ route('admin.permissions.index') }}");
            });
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Something went wrong!';
            alert("❌ " + msg);
        }
    });
});

</script>
@endsection
