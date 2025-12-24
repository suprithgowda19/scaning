@extends('layouts.master')

@section('title', 'Staff Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

    <style>
        .stat-card {
            border-radius: 10px;
            text-align: center;
        }
        .stat-card h6 {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .stat-card h2 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }
        .stat-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .card-body.compact {
            padding: 14px;
        }

        /* Compact filters */
        .filter-row input,
        .filter-row select,
        .filter-row button {
            height: 36px;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')

{{-- ================= KPIs ================= --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body compact">
                <h6>Capacity</h6>
                <h2>{{ $stats['capacity'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card border-success">
            <div class="card-body compact">
                <h6>Entered</h6>
                <h2 class="text-success">{{ $stats['entered'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card border-warning">
            <div class="card-body compact">
                <h6>Remaining</h6>
                <h2 class="text-warning">{{ $stats['remaining'] }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- ================= CATEGORY CARDS ================= --}}
<div class="row mb-4">
    @foreach ($stats['categories'] as $category => $count)
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body compact">
                    <h6 class="text-truncate" title="{{ $category }}">
                        {{ $category }}
                    </h6>
                    <h3>{{ $count }}</h3>
                </div>
            </div>
        </div>
    @endforeach
</div>

<hr>

{{-- ================= FILTERS ================= --}}
<h5 class="mb-2">Scan Reports</h5>

<div class="row g-2 align-items-end filter-row mb-3">
    <div class="col-md-3">
        <label class="form-label mb-1">Date</label>
        <input type="date" id="filter-date" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="form-label mb-1">Category</label>
        <select id="filter-category" class="form-select">
            <option value="">All Categories</option>
            @foreach ($stats['categories'] as $category => $count)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button id="apply-filter" class="btn btn-primary w-100">
            Filter
        </button>
    </div>

    <div class="col-md-4 d-flex justify-content-end gap-2">
        <a href="{{ route('staff.dashboard.export.excel') }}"
           class="btn btn-success btn-sm">
            Excel
        </a>

        {{-- PDF placeholder --}}
        <button type="button" class="btn btn-danger btn-sm">
            PDF
        </button>
    </div>
</div>

{{-- ================= REPORT TABLE ================= --}}
<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Form No</th>
                <th>Delegate Name</th>
                <th>Mobile</th>
                <th>Category</th>
                <th>Status</th>
                <th>Scanned At</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></cript>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

    <script>
        feather.replace();

        const table = $('#data-source-1').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('staff.dashboard') }}",
                data: function (d) {
                    d.date = document.getElementById('filter-date').value;
                    d.category = document.getElementById('filter-category').value;
                    d.ajax = 1; // flag for controller if needed
                }
            },
            columns: [
                { data: 'index' },
                { data: 'form_no' },
                { data: 'name' },
                { data: 'phone' },
                { data: 'category' },
                { data: 'status' },
                { data: 'scanned_at' },
            ]
        });

        document.getElementById('apply-filter').addEventListener('click', function () {
            table.ajax.reload();
        });
    </script>
@endpush
