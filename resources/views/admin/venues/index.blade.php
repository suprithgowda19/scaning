@extends('layouts.master')

@section('title', 'Venues')
@section('page_title', 'Venues')

@section('breadcrumb')
    <li class="breadcrumb-item">Venues</li>
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

    <a href="{{ route('admin.venues.create') }}" class="btn btn-primary text-white">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Venue
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="venuesTable" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Venue Name</th>
                <th>Address</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($venues as $index => $venue)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $venue->name }}</td>

                    <td>{{ $venue->address ?? 'N/A' }}</td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- EDIT --}}
                            <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.venues.edit', $venue->id) }}'"
                                    title="Edit">
                                <i data-feather="edit" class="icon-18"></i>
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.venues.destroy', $venue->id) }}"
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

    // Datatable
    $('#venuesTable').DataTable({
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
                title: "Delete Venue?",
                text: "This venue and all related data will be removed.",
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

    // Success toast
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
