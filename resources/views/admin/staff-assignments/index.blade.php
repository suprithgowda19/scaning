@extends('layouts.master')

@section('title', 'Staff Assignments')
@section('page_title', 'Staff → Screen Assignments')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.staff-assignments.index') }}">
            Staff Assignments
        </a>
    </li>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

<style>
    .btn-square {
        width: 38px;
        height: 38px;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
    }
    .icon-18 { width: 18px; height: 18px; }

    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 22px;
    }
    
    input:checked + .slider { background-color: #4caf50; }
    input:checked + .slider:before { transform: translateX(24px); }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"></h4>

    <a href="{{ route('admin.staff-assignments.create') }}" class="btn btn-primary">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Assign Screen
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Staff</th>
                <th>Venue</th>
                <th>Screen</th>
                
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($assignments as $index => $assignment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $assignment->user->name }}</td>
                    <td>{{ $assignment->venue->name }}</td>
                    <td>{{ $assignment->screen->name }}</td>

              
                    <td class="d-flex gap-2">

                        <button type="button"
                                class="btn btn-primary btn-square"
                                onclick="window.location.href='{{ route('admin.staff-assignments.edit', $assignment->id) }}'">
                            <i data-feather="edit" class="icon-18"></i>
                        </button>

                        <form action="{{ route('admin.staff-assignments.destroy', $assignment->id) }}"
                              method="POST"
                              onsubmit="return false;">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="btn btn-danger btn-square"
                                    onclick="confirmRevoke(this.form)">
                                <i data-feather="trash-2" class="icon-18"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   

</script>
@endpush
