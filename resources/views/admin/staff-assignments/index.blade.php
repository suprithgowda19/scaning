@extends('layouts.master')

@section('title', 'Staff Assignments')
@section('page_title', 'Staff → Screen Assignments')

@section('breadcrumb')
    <li class="breadcrumb-item">Staff Assignments</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/datatables.css') }}">
<style>
    .btn-square {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
    .icon-18 { width: 18px; height: 18px; }
    tr.inactive { opacity: 0.55; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.staff-assignments.create') }}" class="btn btn-primary">
        <i data-feather="plus-circle" class="icon-18 me-1"></i>
        Assign Screen
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Staff</th>
                <th>Venue</th>
                <th>Screen</th>
                <th>Status</th>
               
            </tr>
        </thead>

        <tbody>

            @if ($errors->has('assignment'))
                <tr>
                    <td colspan="6" class="text-danger fw-semibold">
                        {{ $errors->first('assignment') }}
                    </td>
                </tr>
            @endif

            @foreach ($assignments as $i => $assignment)
                <tr class="{{ !$assignment->active ? 'inactive' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $assignment->user->name }}</td>
                    <td>{{ $assignment->venue->name }}</td>
                    <td>{{ $assignment->screen->name }}</td>
                    <td>{{ $assignment->active ? 'Active' : 'Inactive' }}</td>
                    
                </tr>
            @endforeach

        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    feather.replace();
});
</script>
@endpush
