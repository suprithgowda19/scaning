@extends('layouts.master')

@section('title', 'Scan Tickets')
@section('page_title', 'Scan Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Scan</li>
@endsection

@push('css')
<style>
    .stat-card {
        border-radius: 10px;
        text-align: center;
    }
    .stat-card h6 {
        color: #6b7280;
        font-size: 13px;
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

    .scan-box {
        max-width: 100%;
    }
    .scan-input {
        font-size: 18px;
        height: 52px;
    }
    .scan-status {
        font-size: 16px;
        font-weight: 600;
    }

    .scan-success { color:#198754; }
    .scan-error   { color:#dc3545; }
    .scan-warning { color:#ffc107; }

    .card-body.compact {
        padding: 14px;
    }

    .recent-card {
        border-left: 4px solid #198754;
        background: #f8fff9;
    }
</style>
@endpush

@section('content')

<div class="row">

    {{-- ================= LEFT : STATS ================= --}}
    <div class="col-lg-7">

        {{-- KPIs --}}
        <div class="row mb-3">
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

        {{-- CATEGORY CARDS --}}
        <div class="row mb-3">
            @forelse ($stats['categories'] as $category => $count)
                <div class="col-md-6 col-xl-3 mb-3">
                    <div class="card stat-card h-100">
                        <div class="card-body compact">
                            <h6 class="text-truncate" title="{{ $category }}">
                                {{ $category }}
                            </h6>
                            <h3>{{ $count }}</h3>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    No scans yet
                </div>
            @endforelse
        </div>

        {{-- ================= RECENT ENTRY ================= --}}
        <div class="row">
            <div class="col-12">
                <div id="recent-entry" class="card recent-card d-none">
                    <div class="card-body compact">
                        <h6 class="mb-2">Most Recent Entry</h6>
                        <p class="mb-1">
                            <strong>Form No:</strong>
                            <span id="recent-form"></span>
                        </p>
                        <p class="mb-1">
                            <strong>Name:</strong>
                            <span id="recent-name"></span>
                        </p>
                        <p class="mb-0">
                            <strong>Category:</strong>
                            <span id="recent-category"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= RIGHT : SCAN ================= --}}
    <div class="col-lg-5">

        <div class="card scan-box">
            <div class="card-header text-center">
                <h5 class="mb-0">QR / UUID Scan</h5>
            </div>

            <div class="card-body">

                <form id="scan-form">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Scan QR Code / Enter UUID
                        </label>
                        <input
                            type="text"
                            name="uuid"
                            id="scan-input"
                            class="form-control scan-input"
                            placeholder="Scan or type UUID"
                            autocomplete="off"
                            autofocus
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Scan
                    </button>
                </form>

                <div id="scan-result" class="mt-3 text-center scan-status"></div>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
const recentKey = 'lastSuccessfulScan';

/* Restore recent entry on load */
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem(recentKey);
    if (!saved) return;

    const data = JSON.parse(saved);
    showRecent(data);
});

function showRecent(data) {
    document.getElementById('recent-form').textContent = data.form_no;
    document.getElementById('recent-name').textContent = data.name;
    document.getElementById('recent-category').textContent = data.category;
    document.getElementById('recent-entry').classList.remove('d-none');
}

document.getElementById('scan-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const input = document.getElementById('scan-input');
    const resultBox = document.getElementById('scan-result');

    resultBox.textContent = 'Scanning...';
    resultBox.className = 'scan-status';

    try {
        const response = await fetch("{{ route('staff.scan.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ uuid: input.value })
        });

        const data = await response.json();

        if (data.status === 'valid') {
            resultBox.textContent = '✔ Entry Allowed';
            resultBox.classList.add('scan-success');

            // Persist recent entry
            localStorage.setItem(recentKey, JSON.stringify(data.delegate));

            // Reload to refresh stats (recent entry will persist)
            location.reload();
        }
        else if (data.status === 'duplicate') {
            resultBox.textContent = '⚠ Already Scanned for this Screen';
            resultBox.classList.add('scan-warning');
        }
        else {
            resultBox.textContent = '✖ ' + (data.message ?? 'Rejected');
            resultBox.classList.add('scan-error');
        }

    } catch {
        resultBox.textContent = 'Server Error';
        resultBox.classList.add('scan-error');
    }

    input.value = '';
    input.focus();
});
</script>
@endpush
