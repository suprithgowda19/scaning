@extends('layouts.master')

@section('title', 'Show Assignments')
@section('page_title', 'Show Assignments')

@section('breadcrumb')
    <li class="breadcrumb-item">Show Assignments</li>
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

    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 22px;
    }
    .switch input { display: none; }

    .slider {
        position: absolute;
        cursor: pointer;
        background-color: #dadada;
        border-radius: 34px;
        top: 0; left: 0; right: 0; bottom: 0;
        transition: .3s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .3s;
    }
    input:checked + .slider {
        background-color: #4caf50;
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.ssa.create') }}" class="btn btn-primary text-white">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Assign Show
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="ssaTable" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Venue</th>
                <th>Screen</th>
                <th>Day</th>
                <th>Slot</th>
                <th>Movie</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($assignments as $index => $ssa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ssa->venue->name }}</td>
                <td>{{ $ssa->screen->name }}</td>
                <td>Day {{ $ssa->day }}</td>
                <td>{{ \Carbon\Carbon::parse($ssa->slot->start_time)->format('h:i A') }}</td>
                <td>{{ $ssa->movie->title }}</td>

                {{-- STATUS --}}
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <label class="switch">
                            <input type="checkbox"
                                   class="ssa-toggle"
                                   data-id="{{ $ssa->id }}"
                                   {{ $ssa->status === 'active' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>

                        <span class="{{ $ssa->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ strtoupper($ssa->status) }}
                        </span>
                    </div>
                </td>

                {{-- ACTIONS --}}
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">

                        <button class="btn btn-info btn-square"
                                onclick="location.href='{{ route('admin.ssa.show', $ssa->id) }}'">
                            <i data-feather="eye" class="icon-18"></i>
                        </button>

                        <button class="btn btn-primary btn-square"
                                onclick="location.href='{{ route('admin.ssa.edit', $ssa->id) }}'">
                            <i data-feather="edit" class="icon-18"></i>
                        </button>

                        <form action="{{ route('admin.ssa.destroy', $ssa->id) }}"
                              method="POST"
                              class="delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="btn btn-danger btn-square delete-btn">
                                <i data-feather="trash-2" class="icon-18"></i>
                            </button>
                        </form>

                    </div>
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
document.addEventListener("DOMContentLoaded", function () {

    feather.replace();

    $('#ssaTable').DataTable({
        pagingType: "simple_numbers"
    });

    // DELETE CONFIRM
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            const form = this.closest("form");

            Swal.fire({
                title: "Delete Show Assignment?",
                text: "This show will be removed from the schedule.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it"
            }).then(res => {
                if (res.isConfirmed) form.submit();
            });
        });
    });

    // STATUS TOGGLE
    document.querySelectorAll('.ssa-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {

            const ssaId = this.dataset.id;
            const status = this.checked ? 'active' : 'inactive';

            fetch(`{{ url('admin/ssa') }}/${ssaId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            })
            .then(res => res.json())
            .then(() => {
                Swal.fire({
                    icon: "success",
                    title: "Updated",
                    text: `Show is now ${status.toUpperCase()}`,
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => location.reload());
            })
            .catch(() => {
                Swal.fire("Error", "Could not update status", "error");
                this.checked = !this.checked;
            });

        });
    });

    @if (session('success'))
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false,
            position: "center"
        });
    @endif

});
</script>
@endpush
