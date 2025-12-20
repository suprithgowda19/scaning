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

        .icon-18 {
            width: 18px;
            height: 18px;
        }
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"></h4>

        <a href="{{ route('admin.slots.create') }}" class="btn btn-primary text-white">
            <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Slot
        </a>
    </div>

    <div class="table-responsive">
        <table class="display" id="data-source-1" style="width:100%">
            <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Venue</th>
                    <th>Start Time</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($slots as $index => $slot)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>{{ $slot->venue->name }}</td>

                        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>

                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-2">

                                {{-- EDIT --}}
                                <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.slots.edit', $slot->id) }}'"
                                    title="Edit">
                                    <i data-feather="edit" class="icon-18"></i>
                                </button>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.slots.destroy', $slot->id) }}"
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

            // Feather icons initialize
            feather.replace();

            // SweetAlert Delete Confirmation (EXACTLY LIKE VENUES)
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const form = this.closest("form");

                    Swal.fire({
                        title: "Delete Slot?",
                        text: "This slot will be deleted permanently.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Success Popup
            @if (session('success'))
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonColor: "#3085d6"
                });
            @endif

        });
    </script>
@endpush
