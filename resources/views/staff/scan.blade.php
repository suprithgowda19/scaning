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
                    <strong>Movie:</strong> {{ $activeSSA->movie->title }} |
                    <strong>Language:</strong> {{ $activeSSA->movie->language }}
                </p>
                <p class="mb-0">
                    <strong>Slot:</strong> {{ $activeSSA->slot->start_time }} |
                    <strong>Runtime:</strong> {{ $activeSSA->movie->duration }} mins
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <small>Capacity</small>
                        <h3>{{ $stats['capacity'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <small>Entered</small>
                        <h3 class="text-success">{{ $stats['entered'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <small>Remaining</small>
                        <h3 class="text-warning">{{ $stats['remaining'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($stats['categories'] as $cat => $count)
                <div class="col-md-3 mb-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <small>{{ $cat }}</small>
                            <h4>{{ $count }}</h4>
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

                <form id="scan-form">
                    @csrf
                    <input type="text"
                        id="scan-input"
                        class="form-control mb-3"
                        placeholder="Scan QR or type Form No"
                        autofocus
                        required
                    >

                    <button class="btn btn-primary w-100">
                        Scan
                    </button>
                </form>

                <div id="scan-result" class="mt-3 fw-bold text-center"></div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('scan-form').addEventListener('submit', async e => {
    e.preventDefault();

    const input = document.getElementById('scan-input');
    const result = document.getElementById('scan-result');

    result.textContent = 'Scanning...';

    const res = await fetch("{{ route('staff.scan.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ uuid: input.value })
    });

    const data = await res.json();

    if (data.status === 'valid') {
        result.textContent = `✔ ${data.delegate.name} (${data.delegate.category})`;
        location.reload();
    } else {
        result.textContent = data.message || 'Rejected';
    }

    input.value = '';
    input.focus();
});
</script>
@endpush
