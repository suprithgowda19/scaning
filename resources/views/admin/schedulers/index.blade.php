@extends('layouts.master')

@section('title', 'Schedulers')
@section('page_title', 'Schedulers')

@section('breadcrumb')
    <li class="breadcrumb-item">Schedulers</li>
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

    <div class="d-flex gap-2">
        {{-- Import button (modal trigger) --}}
        <button class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#importModal">
            <i data-feather="upload" class="icon-18 me-1"></i>
            Import Excel
        </button>

        {{-- Add schedule --}}
        <a href="{{ route('admin.schedulers.create') }}"
           class="btn btn-primary text-white">
            <i data-feather="plus-circle" class="icon-18 me-1"></i>
            Add Schedule
        </a>
    </div>
</div>

{{-- Import summary --}}
@if (session('import_summary'))
    <div class="alert alert-info">
        <strong>Import Summary:</strong>
        Created: {{ session('import_summary.created') }},
        Updated: {{ session('import_summary.updated') }},
        Failed: {{ session('import_summary.failed') }}
    </div>
@endif

<div class="table-responsive">
    <table class="display" id="schedulerTable" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Venue</th>
                <th>Screen</th>
                <th>Title</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($schedulers as $index => $scheduler)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $scheduler->venue->name }}</td>
                    <td>{{ $scheduler->screen->name }}</td>
                    <td>
                        {{ $scheduler->movie_title ?? $scheduler->event_title }}
                    </td>
                    <td>{{ $scheduler->show_date->format('d-m-Y') }}</td>
                    <td>{{ $scheduler->start_time }}</td>
                    <td>
                        @if($scheduler->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- VIEW --}}
                            <button class="btn btn-info btn-square"
                                    onclick="window.location.href='{{ route('admin.schedulers.show', $scheduler->id) }}'">
                                <i data-feather="eye" class="icon-18"></i>
                            </button>

                            {{-- EDIT --}}
                            <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.schedulers.edit', $scheduler->id) }}'">
                                <i data-feather="edit" class="icon-18"></i>
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.schedulers.destroy', $scheduler->id) }}"
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

{{-- ================= IMPORT MODAL ================= --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('admin.schedulers.import') }}"
              enctype="multipart/form-data"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Import Scheduler (Excel)</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">Upload Excel File</label>
                <input type="file"
                       name="file"
                       class="form-control"
                       required
                       accept=".xlsx,.xls,.csv">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Import
                </button>
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    feather.replace();

    $('#schedulerTable').DataTable({
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
                title: "Delete Schedule?",
                text: "This schedule will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
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
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
