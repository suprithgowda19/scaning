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

    .icon-18 {
        width: 18px;
        height: 18px;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"></h4>

    <a href="{{ route('admin.ssa.create') }}" class="btn btn-primary text-white">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Assign Show
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="ssaTable" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Venue</th>
                <th>Screen</th>
                <th>Day</th>
                <th>Slot</th>
                <th>Movie</th>
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

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- VIEW --}}
                            <button class="btn btn-info btn-square"
                                    onclick="window.location.href='{{ route('admin.ssa.show', $ssa->id) }}'"
                                    title="View">
                                <i data-feather="eye" class="icon-18"></i>
                            </button>

                            {{-- EDIT --}}
                            <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.ssa.edit', $ssa->id) }}'"
                                    title="Edit">
                                <i data-feather="edit" class="icon-18"></i>
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.ssa.destroy', $ssa->id) }}"
                                  method="POST"
                                  class="delete-form">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-square delete-btn"
                                        title="Delete">
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

{{-- Datatables --}}
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    feather.replace();

    // DataTable init
    $('#ssaTable').DataTable({
        pagingType: "simple_numbers",
        language: {
            paginate: {
                previous: "Previous",
                next: "Next"
            }
        }
    });

    // Delete confirmation
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            const form = this.closest("form");

            Swal.fire({
                title: "Delete Show Assignment?",
                text: "This show will be removed from the schedule.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Success popup
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
