@extends('layouts.master')

@section('title', 'Staff Scan Reports')

@section('content')
<div class="container-fluid">

{{-- FILTERS --}}
<div class="row g-2 mb-3 align-items-end">

    <div class="col-md-3">
        <label class="form-label">Day</label>
        <select id="show_date" class="form-select">
            <option value="">All Days</option>
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
        <select id="movie_title" class="form-select">
            <option value="">All Movies</option>
            @foreach ($movies as $movie)
                <option value="{{ $movie }}">{{ $movie }}</option>
            @endforeach
        </select>
    </div>

</div>

{{-- ACTIONS --}}
<div class="mb-3 d-flex gap-2">
    <button class="btn btn-primary" onclick="loadData(true)">Apply</button>
    <button class="btn btn-outline-secondary" onclick="resetFilters()">Reset</button>
    <a id="exportLink" class="btn btn-success" target="_blank">Export Excel</a>
</div>

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

function loadData(updateSlots = false) {
    const params = {
        show_date: dateEl.value,
        slot_no: slotEl.value,
        movie_title: movieEl.value
    };

    exportEl.href =
        `{{ route('dashboard.staff.export.excel') }}?` +
        new URLSearchParams(params);

    fetch(`{{ route('dashboard.staff.ajax.filter') }}?` +
        new URLSearchParams(params))
        .then(r => r.json())
        .then(res => {
            renderTable(res.logs || []);
            if (updateSlots) updateSlotsDropdown(res.slots || []);
        });
}

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

function updateSlotsDropdown(slots) {
    slotEl.innerHTML = '<option value="">All Slots</option>';
    if (!slots.length) {
        slotEl.disabled = true;
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

function resetFilters() {
    dateEl.value = '';
    slotEl.value = '';
    movieEl.value = '';
    slotEl.disabled = true;
    loadData(false);
}

dateEl.addEventListener('change', () => loadData(true));
movieEl.addEventListener('change', () => loadData(false));
slotEl.addEventListener('change', () => loadData(false));

document.addEventListener('DOMContentLoaded', () => loadData(false));
</script>
@endpush
