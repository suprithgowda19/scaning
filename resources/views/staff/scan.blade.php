@extends('layouts.master')

@section('title', 'Scan Tickets')
@section('page_title', 'Scan Dashboard')

@section('content')
<div class="row">

    {{-- LEFT : SHOW + STATS --}}
    <div class="col-lg-7">

        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $screen->name }}</h5>

                <p class="mb-1">
                    <strong>Movie:</strong>
                    {{ $activeSSA->movie->title ?? '—' }}
                    |
                    <strong>Language:</strong>
                    {{ $activeSSA->movie->language ?? '—' }}
                </p>

                <p class="mb-0">
                    <strong>Slot:</strong>
                    {{ $activeSSA->slot->start_time }}
                    |
                    <strong>Runtime:</strong>
                    {{ $activeSSA->movie->duration ?? 120 }} mins
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <small>Capacity</small>
                        <h3 id="capacity">{{ $stats['capacity'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <small>Entered</small>
                        <h3 id="entered" class="text-success">
                            {{ $stats['entered'] }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <small>Remaining</small>
                        <h3 id="remaining" class="text-warning">
                            {{ $stats['remaining'] }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="category-stats">
            @foreach ($stats['categories'] as $cat => $count)
                <div class="col-md-3 mb-2" data-category="{{ $cat }}">
                    <div class="card text-center">
                        <div class="card-body">
                            <small>{{ $cat }}</small>
                            <h4 class="cat-count">{{ $count }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- RIGHT : SCAN --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header text-center">
                <h5>Scan QR / UUID / Form No</h5>
            </div>

            <div class="card-body">
                <input
                    type="text"
                    id="scan-input"
                    class="form-control mb-3"
                    placeholder="Scan QR or type Form No"
                    autocomplete="off"
                    autofocus
                >

                <button
                    type="button"
                    id="scan-btn"
                    class="btn btn-primary w-100"
                >
                    Scan
                </button>

                <div
                    id="scan-result"
                    class="mt-3 fw-bold text-center"
                ></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const input   = document.getElementById('scan-input');
const button  = document.getElementById('scan-btn');
const result  = document.getElementById('scan-result');
const entered = document.getElementById('entered');
const remain  = document.getElementById('remaining');

async function performScan() {
    const value = input.value.trim();
    if (!value) return;

    result.textContent = 'Scanning…';

    try {
        const res = await fetch("{{ route('staff.scan.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ uuid: value })
        });

        const data = await res.json();

        if (data.status === 'valid') {
            result.textContent =
                `✔ ${data.delegate.name} (${data.delegate.category})`;

            entered.textContent = Number(entered.textContent) + 1;
            remain.textContent  = Number(remain.textContent) - 1;

            const cat = document.querySelector(
                `[data-category="${data.delegate.category}"] .cat-count`
            );
            if (cat) {
                cat.textContent = Number(cat.textContent) + 1;
            }

        } else {
            result.textContent = data.message || 'Rejected';
        }

    } catch (e) {
        result.textContent = 'Scan failed';
    }

    input.value = '';
    input.focus();
}

button.addEventListener('click', performScan);

// USB scanner ENTER handling
input.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        performScan();
    }
});

// Hard focus lock (scanner-safe)
setInterval(() => {
    if (document.activeElement !== input) {
        input.focus();
    }
}, 300);
</script>
@endpush
