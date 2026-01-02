@extends('layouts.master')

@section('content')
<div class="container-fluid">

{{-- FILTERS --}}
<div class="row g-2 mb-2">
    <div class="col-md-2">
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
        <select id="screen_id" class="form-select">
            <option value="">All Screens</option>
            @foreach ($screens as $screen)
                <option value="{{ $screen->id }}">{{ $screen->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select id="slot_no" class="form-select">
            <option value="">All Slots</option>
        </select>
    </div>

    <div class="col-md-3">
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
    <button class="btn btn-primary" onclick="loadData()">Apply</button>
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
    <th>Screen</th>
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
const screenEl = document.getElementById('screen_id');
const slotEl   = document.getElementById('slot_no');
const movieEl  = document.getElementById('movie_title');
const tbody    = document.getElementById('table-body');
const exportEl = document.getElementById('exportLink');

function loadData() {
    const params = {
        show_date: dateEl.value,
        screen_id: screenEl.value,
        slot_no: slotEl.value,
        movie_title: movieEl.value
    };

    exportEl.href = `{{ route('dashboard.admin.export.excel') }}?`
        + new URLSearchParams(params);

    fetch(`{{ route('dashboard.admin.ajax.filter') }}?`
        + new URLSearchParams(params))
        .then(r => r.json())
        .then(res => {
            renderTable(res.logs);
            updateSlots(res.slots);
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
                <td>${log.delegate?.firstname ?? ''}</td>
                <td>${log.screen?.name ?? '-'}</td>
                <td>${log.scheduler?.movie_title ?? '-'}</td>
                <td>Slot ${log.slot_no}</td>
                <td>${log.scanned_at ?? '-'}</td>
            </tr>
        `);
    });
}

function updateSlots(slots) {
    const current = slotEl.value;
    slotEl.innerHTML = '<option value="">All Slots</option>';

    slots.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.slot_no;
        opt.textContent = s.label;
        if (current == s.slot_no) opt.selected = true;
        slotEl.appendChild(opt);
    });
}

function resetFilters() {
    dateEl.value = '';
    screenEl.value = '';
    slotEl.value = '';
    movieEl.value = '';
    loadData();
}

dateEl.addEventListener('change', loadData);
screenEl.addEventListener('change', loadData);

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endpush
