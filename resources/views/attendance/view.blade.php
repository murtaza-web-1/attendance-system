@extends('layouts.app')

@section('content')
<div class="container mt-5">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="mb-4">Your Attendance Records for users</h2>

    @if($records->isEmpty())
        <div class="alert alert-info">No attendance records found.</div>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                        <td>
                            @if($record->status === 'present')
                                <span class="badge bg-success">Present</span>
                            @elseif($record->status === 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @elseif($record->status === 'leave')
                                <span class="badge bg-warning text-dark">Leave</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
