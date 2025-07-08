@section('content')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="color: green; font-weight: bold; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; font-weight: bold; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <h2>Your Attendance Records</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->date }}</td>
                    <td>{{ ucfirst($record->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
