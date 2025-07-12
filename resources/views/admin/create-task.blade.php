@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Task</h2>
    
    <form action="{{ route('admin.storeTask') }}" method="POST">
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

        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
</div>
@endsection

@section('scripts')
<!-- Include CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
@endsection
