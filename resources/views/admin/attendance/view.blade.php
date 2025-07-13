{{-- 📄 Full Page View --}}
@extends('layouts.admin') {{-- Or use layouts.app for normal user --}}

@section('content')
    @include('admin.attendance.partial') {{-- Load inner content --}}
@endsection
