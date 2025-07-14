@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="mb-4 text-center">📋 My Assigned Tasks</h3>

    @if($tasks->isEmpty())
        <div class="alert alert-info">You have no tasks assigned yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light text-center">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Admin Feedback</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($tasks as $task)
<tr>
    <td>{{ $task->title }}</td>
    <td>{!! $task->description !!}</td>
    <td>
        <span class="badge bg-{{ $task->status === 'submitted' ? 'success' : 'warning' }}">
            {{ ucfirst($task->status) }}
        </span>
    </td>
    <td>{{ $task->admin_feedback ?? '—' }}</td>
</tr>
@endforeach

                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
