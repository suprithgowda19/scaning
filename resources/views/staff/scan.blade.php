@extends('layouts.master')

@section('title', 'Scan Dashboard')

@section('content')
<style>
    .kpi-inline {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }
    .kpi-box {
        flex: 1;
        text-align: center;
        padding: 6px;
        border-radius: 6px;
        background: #f8f9fa;
    }
    .kpi-label {
        font-size: 11px;
        color: #6c757d;
    }
    .kpi-value {
        font-size: 18px;
        font-weight: 700;
    }
</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-control fw-bold text-center py-1">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-control fw-bold text-center py-1">
                Screen : {{ $screen->name }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-control fw-bold text-center py-1">
                Show :
                {{ \Carbon\Carbon::parse($scheduler->start_time)->format('h:i A') }}
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row mb-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <div class="kpi-inline">

                        <div class="kpi-box">
                            <div class="kpi-label">Capacity</div>
                            <div class="kpi-value">{{ $stats['capacity'] }}</div>
                        </div>

                        <div class="kpi-box">
                            <div class="kpi-label">Scanned</div>
                            <div class="kpi-value text-success" id="entered">
                                {{ $stats['entered'] }}
                            </div>
                        </div>

                        <div class="kpi-box">
                            <div class="kpi-label">Remaining</div>
                            <div class="kpi-value text-warning" id="remaining">
                                {{ $stats['remaining'] }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body py-2">

                    <div class="fw-bold text-white text-center py-1 mb-1"
                         style="background:#1a237e;border-radius:4px;">
                        Scan QR / UUID
                    </div>

                    <input
                        type="text"
                        id="scan_input"
                        class="form-control fw-bold text-center mb-1"
                        placeholder="Scan or type UUID / Form No"
                        autocomplete="off"
                        autofocus
                        style="height:42px;font-size:16px;"
                    >

                    <div class="border rounded text-center mb-2 py-1" style="height:48px;">
                        <div class="fw-bold" id="scan_status">Awaiting Scan...</div>
                        <div class="fw-bold text-danger" id="scan_error"></div>
                    </div>

                    {{-- CATEGORY GRID --}}
                    <div class="row g-1" id="category_grid">
                        @forelse ($stats['categories'] as $category => $count)
                            <div class="col-6">
                                <div class="card shadow-sm text-center py-1">
                                    <small class="text-muted">{{ $category }}</small>
                                    <div class="fw-bold"
                                         data-category="{{ $category }}">
                                        {{ $count }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted small">
                                No scans yet
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body py-2">

                    <div class="fw-bold text-white text-center py-1 mb-1"
                         style="background:#1a237e;border-radius:4px;">
                        Ticket Details
                    </div>

                    <div id="ticket_details" class="small text-muted text-center">
                        Please scan to see details
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const input     = document.getElementById('scan_input');
const statusEl  = document.getElementById('scan_status');
const errorEl   = document.getElementById('scan_error');
const detailsEl = document.getElementById('ticket_details');
const enteredEl = document.getElementById('entered');
const remainEl  = document.getElementById('remaining');

input.addEventListener('keydown', async (e) => {
    if (e.key !== 'Enter') return;

    const value = input.value.trim();
    if (!value) return;

    statusEl.textContent = 'Scanning...';
    statusEl.className = 'fw-bold';
    errorEl.textContent = '';

    try {
        const res = await fetch("{{ route('staff.scan.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ uuid: value })
        });

        const data = await res.json();

        if (data.status === 'valid') {
            statusEl.textContent = '✔ Entry Allowed';
            statusEl.className = 'fw-bold text-success';

            detailsEl.innerHTML = `
                <div class="text-start">
                    <div><b>Form:</b> ${data.delegate.form_no}</div>
                    <div><b>Name:</b> ${data.delegate.name}</div>
                    <div><b>Category:</b> ${data.delegate.category}</div>
                    <hr class="my-1">
                    <div><b>Show:</b> ${data.show.title}</div>
                    <div><b>Start:</b> ${data.show.start_time}</div>
                </div>
            `;

            enteredEl.textContent = data.stats.entered;
            remainEl.textContent  = data.stats.remaining;

            
            for (const [category, count] of Object.entries(data.stats.categories)) {
                const el = document.querySelector(`[data-category="${category}"]`);
                if (el) el.textContent = count;
            }

        } else if (data.status === 'duplicate') {
            statusEl.textContent = '⚠ Already Scanned';
            statusEl.className = 'fw-bold text-warning';
        } else {
            statusEl.textContent = '✖ Rejected';
            statusEl.className = 'fw-bold text-danger';
            errorEl.textContent = data.message ?? 'Invalid code';
        }

    } catch {
        statusEl.textContent = '✖ Server Error';
        statusEl.className = 'fw-bold text-danger';
        errorEl.textContent = 'Try again';
    }

    input.value = '';
    input.focus();
});
</script>
@endpush
