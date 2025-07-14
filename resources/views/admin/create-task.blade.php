@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card p-4 shadow-sm">

        <h3 class="mb-4 text-center">📝 Create New Task</h3>

        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form id="taskForm" action="{{ route('admin.storeTask') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Task Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Task Description</label>
                <textarea name="description" id="editor" class="form-control" rows="8"></textarea>
            </div>

            <div class="mb-3">
                <label for="user_id" class="form-label">Assign to User (ID)</label>
                <input type="number" name="user_id" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Create Task</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#taskForm').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#messageBox').html('<div class="alert alert-success">✅ Task created successfully!</div>');
                $('#taskForm')[0].reset(); // Optional: reset form
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                errorHtml += '</ul></div>';
                $('#messageBox').html(errorHtml);
            }
        });
    });
</script>

@endsection
