@extends('layouts.master')

@section('title', 'Staff Scan Reports')
@section('page_title', 'Staff Scan Reports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Staff Reports</li>
@endsection

@push('css')
<style>
    .filter-row select {
        height: 36px;
        font-size: 13px;
    }
    table td, table th {
        font-size: 13px;
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('dashboard.staff.index') }}">

        <div class="row g-2 filter-row mb-2">

            <div class="col-md-2">
                <label class="form-label mb-1">Day</label>
                <select name="day" class="form-select">
                    <option value="">All Days</option>
                    @for ($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}"
                            {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>
                            Day {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1">Slot</label>
                <select name="slot_id" class="form-select">
                    <option value="">All Slots</option>
                    @foreach ($slots as $slot)
                        <option value="{{ $slot->id }}"
                            {{ ($filters['slot_id'] ?? '') == $slot->id ? 'selected' : '' }}>
                            {{ $slot->start_time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label mb-1">Movie</label>
                <select name="movie_id" class="form-select">
                    <option value="">All Movies</option>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->id }}"
                            {{ ($filters['movie_id'] ?? '') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->title }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-primary">Apply</button>

                <a href="{{ route('dashboard.staff.index') }}"
                   class="btn btn-outline-secondary">
                    Reset
                </a>

                <a href="{{ route('dashboard.staff.export.excel', request()->query()) }}"
                   class="btn btn-success">
                    Export Excel
                </a>
            </div>
        </div>

    </form>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Form No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Movie</th>
                        <th>Slot</th>
                        <th>Day</th>
                        <th>Scanned At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $log->form_no }}</td>
                            <td>
                                {{ optional($log->delegate)
                                    ? trim($log->delegate->firstname . ' ' . $log->delegate->lastname)
                                    : '-' }}
                            </td>
                            <td>{{ $log->delegate->phone ?? '-' }}</td>
                            <td>{{ $log->screenSlotAssignment?->movie?->title ?? '-' }}</td>
                            <td>{{ $log->slot->start_time ?? '-' }}</td>
                            <td>Day {{ $log->day }}</td>
                            <td>{{ optional($log->scanned_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
