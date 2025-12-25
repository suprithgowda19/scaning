@extends('layouts.master')

@section('title', 'Slots')
@section('page_title', 'Slots')

@section('breadcrumb')
    <li class="breadcrumb-item">Slots</li>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<style>
    .btn-square {
        width: 40px;
        height: 40px;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px !important;
    }
    .icon-18 { width: 18px; height: 18px; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Slots (Global Time Windows)</h4>

    <a href="{{ route('admin.slots.create') }}" class="btn btn-primary text-white">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Slot
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th class="text-center" style="width:120px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($slots as $index => $slot)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                    </td>

                    <td class="text-center">
                        <a href="{{ route('admin.slots.edit', $slot->id) }}"
                           class="btn btn-primary btn-square"
                           title="Edit Slot">
                            <i data-feather="edit" class="icon-18"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No slots defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    feather.replace();

    @if (session('success'))
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
