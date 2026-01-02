@extends('layouts.master')

@section('title', 'Screens')
@section('page_title', 'Screens')

@section('breadcrumb')
    <li class="breadcrumb-item">Screens</li>
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
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"></h4>

        <a href="{{ route('admin.screens.create') }}" class="btn btn-primary">
            <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Screen
        </a>
    </div>

    <div class="table-responsive">
        <table class="display" id="data-source-1" style="width:100%">
            <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Venue</th>
                    <th>Screen Name</th>
                    <th>Capacity</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($screens as $index => $screen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $screen->venue->name }}</td>
                        <td>{{ $screen->name }}</td>
                        <td>{{ $screen->capacity }}</td>

                        <td class="d-flex gap-2">

                            {{-- EDIT --}}
                            <a href="{{ route('admin.screens.edit', $screen->id) }}"
                               class="btn btn-primary btn-square"
                               title="Edit">
                                <i data-feather="edit" class="icon-18"></i>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.screens.destroy', $screen->id) }}"
                                  method="POST"
                                  onsubmit="return false;">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-square"
                                        onclick="confirmDelete(this.form)"
                                        title="Delete">
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
        feather.replace();

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

        function confirmDelete(form) {
            Swal.fire({
                title: "Delete Screen?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it"
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
