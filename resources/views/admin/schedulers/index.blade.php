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
        {{-- Import --}}
        <button class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#importModal">
            <i data-feather="upload" class="icon-18 me-1"></i>
            Import Excel
        </button>

        {{-- Add --}}
        <a href="{{ route('admin.schedulers.create') }}"
           class="btn btn-primary text-white">
            <i data-feather="plus-circle" class="icon-18 me-1"></i>
            Add Schedule
        </a>
    </div>
</div>

{{-- ================= ONLY MESSAGE ALLOWED ON THIS PAGE ================= --}}
@if (session('import_summary'))
    @php($s = session('import_summary'))

    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Import Summary:</strong>
        Created: {{ $s['created'] ?? 0 }},
        Updated: {{ $s['updated'] ?? 0 }},
        Skipped: {{ $s['skipped'] ?? 0 }},
        Errors: {{ $s['errors'] ?? 0 }}

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
{{-- ==================================================================== --}}

{{-- Validation / delete errors (ONLY real errors) --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
                    <td>{{ optional($scheduler->venue)->name ?? '-' }}</td>
                    <td>{{ optional($scheduler->screen)->name ?? '-' }}</td>
                    <td>{{ $scheduler->movie_title ?? $scheduler->event_title ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($scheduler->show_date)->format('d-m-Y') }}</td>
                    <td>{{ $scheduler->start_time }}</td>
                    <td>
                        @if ($scheduler->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- Edit --}}
                            <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.schedulers.edit', $scheduler->id) }}'">
                                <i data-feather="edit" class="icon-18"></i>
                            </button>

                            {{-- Delete --}}
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
        order: [[4, 'asc'], [5, 'asc']],
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
                text: "Active schedules cannot be deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it"
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>
@endpush
