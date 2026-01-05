@extends('layouts.master')

@section('title', 'Staff Scan Reports')

@section('content')
<div class="container-fluid">

{{-- =========================
   FILTERS
========================= --}}
<div class="row g-2 mb-3 align-items-end">

    <div class="col-md-3">
        <label class="form-label">Day</label>
        <select id="show_date" class="form-select">
            <option value="">Select Day</option>
            @foreach ($dates as $date)
                <option value="{{ $date }}">
                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Slot</label>
        <select id="slot_no" class="form-select" disabled>
            <option value="">All Slots</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Movie</label>
        <select id="movie_title" class="form-select" disabled>
            <option value="">All Movies</option>
        </select>
    </div>

</div>

{{-- =========================
   ACTIONS
========================= --}}
<div class="mb-3 d-flex gap-2">
    <button class="btn btn-primary" onclick="loadData()">Apply</button>
    <button class="btn btn-outline-secondary" onclick="resetFilters()">Reset</button>
    <a id="exportLink" class="btn btn-success" target="_blank">Export Excel</a>
</div>

{{-- =========================
   TABLE
========================= --}}
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
    <th>Scanned At</th>
</tr>
</thead>
<tbody id="table-body">
<tr>
    <td colspan="7" class="text-center text-muted">Loading…</td>
</tr>
</tbody>
</table>

</div>
</div>

</div>
@endsection

@push('scripts')
<script>
const dateEl   = document.getElementById('show_date');
const slotEl   = document.getElementById('slot_no');
const movieEl  = document.getElementById('movie_title');
const tbody    = document.getElementById('table-body');
const exportEl = document.getElementById('exportLink');

/* =========================
   LOAD DATA
========================= */
function loadData() {
    const params = {
        show_date: dateEl.value,
        slot_no: slotEl.value,
        movie_title: movieEl.value
    };

    exportEl.href =
        `{{ route('reports.staff.export.excel') }}?` +
        new URLSearchParams(params);

    fetch(
        `{{ route('reports.staff.ajax.filter') }}?` +
        new URLSearchParams(params)
    )
    .then(r => r.json())
    .then(res => {
        renderTable(res.logs || []);
        updateSlots(res.slots || []);
        updateMovies(res.movies || []);
    });
}

/* =========================
   TABLE
========================= */
function renderTable(logs) {
    tbody.innerHTML = '';

    if (!logs.length) {
        tbody.innerHTML =
            `<tr><td colspan="7" class="text-center text-muted">No records found</td></tr>`;
        return;
    }

    logs.forEach((log, i) => {
        tbody.insertAdjacentHTML('beforeend', `
            <tr>
                <td>${i + 1}</td>
                <td>${log.form_no ?? '-'}</td>
                <td>${log.delegate?.firstname ?? ''} ${log.delegate?.lastname ?? ''}</td>
                <td>${log.delegate?.phone ?? '-'}</td>
                <td>${log.scheduler?.movie_title ?? '-'}</td>
                <td>Slot ${log.slot_no}</td>
                <td>${log.scanned_at ?? '-'}</td>
            </tr>
        `);
    });
}

/* =========================
   SLOT DROPDOWN (DATE DEP)
========================= */
function updateSlots(slots) {
    slotEl.innerHTML = '<option value="">All Slots</option>';

    if (!dateEl.value || !slots.length) {
        slotEl.disabled = true;
        slotEl.value = '';
        return;
    }

    slotEl.disabled = false;

    slots.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.slot_no;
        opt.textContent = s.label;
        slotEl.appendChild(opt);
    });
}

/* =========================
   MOVIE DROPDOWN (DATE DEP)
========================= */
function updateMovies(movies) {
    movieEl.innerHTML = '<option value="">All Movies</option>';

    if (!dateEl.value || !movies.length) {
        movieEl.disabled = true;
        movieEl.value = '';
        return;
    }

    movieEl.disabled = false;

    movies.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m;
        opt.textContent = m;
        movieEl.appendChild(opt);
    });
}

/* =========================
   RESET
========================= */
function resetFilters() {
    dateEl.value = '';
    slotEl.value = '';
    movieEl.value = '';
    slotEl.disabled = true;
    movieEl.disabled = true;
    loadData();
}

/* =========================
   EVENTS
========================= */
dateEl.addEventListener('change', loadData);
slotEl.addEventListener('change', loadData);
movieEl.addEventListener('change', loadData);

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endpush
