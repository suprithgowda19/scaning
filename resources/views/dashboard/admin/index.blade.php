@extends('layouts.master')

@section('title', 'Admin Scan Reports')
@section('page_title', 'Admin Scan Reports')

@section('breadcrumb')
    <li class="breadcrumb-item active">Admin Reports</li>
@endsection

@push('css')
<style>
    .filter-row select {
        height: 36px;
        font-size: 13px;
    }
    table td {
        font-size: 13px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

<form method="GET" action="{{ route('dashboard.admin.index') }}">

    {{-- ================= FILTERS ================= --}}
    <div class="row g-2 mb-2 filter-row">

        <div class="col-md-2">
            <label class="form-label">Day</label>
            <select name="day" class="form-select">
                <option value="">All Days</option>
                @for ($i = 1; $i <= 7; $i++)
                    <option value="{{ $i }}" {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>
                        Day {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Screen</label>
            <select name="screen_id" class="form-select">
                <option value="">All Screens</option>
                @foreach ($screens as $screen)
                    <option value="{{ $screen->id }}"
                        {{ ($filters['screen_id'] ?? '') == $screen->id ? 'selected' : '' }}>
                        {{ $screen->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Slot</label>
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

        <div class="col-md-3">
            <label class="form-label">Movie</label>
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

    {{-- ================= ACTION BUTTONS ================= --}}
    <div class="row mb-3">
        <div class="col-md-6 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Apply
            </button>

            <a href="{{ route('dashboard.admin.index') }}"
               class="btn btn-primary btn-outline">
                Reset
            </a>

            {{-- ✅ FIXED ROUTE --}}
            <a href="{{ route('dashboard.admin.export.excel', request()->query()) }}"
               class="btn btn-success">
                Export Excel
            </a>
        </div>
    </div>

</form>

{{-- ================= TABLE ================= --}}
<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered table-striped">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Form No</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Category</th>
    <th>Screen</th>
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
    <td>{{ optional($log->delegate)?->firstname }} {{ optional($log->delegate)?->lastname }}</td>
    <td>{{ $log->delegate->phone ?? '-' }}</td>
    <td>{{ $log->category }}</td>
    <td>{{ $log->screen->name ?? '-' }}</td>
    <td>{{ $log->screenSlotAssignment?->movie?->title ?? '-' }}</td>
    <td>{{ $log->slot->start_time ?? '-' }}</td>
    <td>Day {{ $log->day }}</td>
    <td>{{ optional($log->scanned_at)->format('Y-m-d H:i:s') }}</td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center text-muted">
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
