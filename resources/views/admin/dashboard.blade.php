@extends('layouts.admin')

@section('content')
@include('layouts.navbar')

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f4f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 30px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    h2 {
        color: #0d47a1;
        margin-bottom: 20px;
    }

    .flash-success {
        color: green;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .flash-error {
        color: red;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .summary-card {
        margin: 20px 0;
        padding: 20px;
        background: #e8f5e9;
        border-left: 5px solid #388e3c;
        border-radius: 10px;
    }

    .summary-card h4 {
        margin-bottom: 12px;
        color: #2e7d32;
    }

    .summary-card ul {
        list-style: none;
        padding-left: 0;
    }

    .summary-card li {
        margin-bottom: 6px;
        font-size: 16px;
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background: #eeeeee;
    }

    td button {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    td form {
        display: inline;
    }

    .btn-delete {
        background-color: #d32f2f;
        color: white;
    }

    .btn-edit {
        background-color: #1976d2;
        color: white;
    }

    .btn-approve {
        background-color: #388e3c;
        color: white;
    }

    .btn-unapprove {
        background-color: #fbc02d;
        color: black;
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        table {
            font-size: 14px;
        }

        td button {
            display: block;
            margin-bottom: 6px;
            width: 100%;
        }
    }
</style>

<div class="container">
    <h2>Welcome to Admin Dashboard</h2>

    {{-- ✅ Flash Messages --}}
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    @if (auth('web')->user()->hasRole('Admin'))
        <p style="color: green;">You are an Admin ✅</p>
    @else
        <p style="color: red;">You are NOT an Admin ❌</p>
    @endif

    {{-- 📊 Attendance Summary --}}
    <div class="summary-card">
        <h4>📊 Attendance Summary</h4>
        <ul>
            <li><strong>✅ Present:</strong> {{ $presentCount }}</li>
            <li><strong>🚫 Absent:</strong> {{ $absentCount }}</li>
            <li><strong>🛑 Leave:</strong> {{ $leaveCount }}</li>
        </ul>
    </div>

    {{-- ✅ Attendance Table --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Leave Approved</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $a->user->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                        <td>
                            @if($a->status === 'Present') ✅ Present
                            @elseif($a->status === 'Absent') 🚫 Absent
                            @elseif($a->status === 'Leave') 🛑 Leave
                            @else ❓ {{ ucfirst($a->status) }}
                            @endif
                        </td>
                        <td>{{ $a->leave_approved ? '✅ Approved' : '❌ Not Approved' }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.edit', $a->id) }}">
                                <button class="btn-edit">✏️ Edit</button>
                            </a>

                            <form method="POST" action="{{ route('admin.attendance.delete', $a->id) }}" onsubmit="return confirm('Are you sure to delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">🗑️ Delete</button>
                            </form>

                            @if($a->status === 'Leave')
                                <form method="POST" action="{{ route('admin.attendance.toggle-leave', $a->id) }}">
                                    @csrf
                                    <button type="submit" class="{{ $a->leave_approved ? 'btn-unapprove' : 'btn-approve' }}">
                                        {{ $a->leave_approved ? '❎ Unapprove' : '✅ Approve' }} Leave
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No attendance records found.</td>
                    </tr>
                @endforelse

                

            </tbody>
        </table>
    </div>
    
<script>
    $(document).ready(function () {
        $('#view-attendance-btn').click(function () {
            $('#attendance-section').html('<p>Loading attendance...</p>');

            $.ajax({
                url: "{{ route('attendance.view.submit') }}",
                method: "GET",
                success: function (data) {
                    let html = `
                        <h3>Your Attendance</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    if (data.length === 0) {
                        html += '<tr><td colspan="2">No records found.</td></tr>';
                    } else {
                        data.forEach(function (record) {
                            html += `<tr><td>${record.date}</td><td>${record.status}</td></tr>`;
                        });
                    }

                    html += '</tbody></table>';
                    $('#attendance-section').html(html);
                },
                error: function () {
                    $('#attendance-section').html('<p style="color:red;">Error loading attendance data.</p>');
                }
            });
        });
    });
</script>
</div>
@endsection
