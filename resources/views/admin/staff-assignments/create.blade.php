@extends('layouts.master')

@section('title', 'Assign Screen')
@section('page_title', 'Assign Screen to Staff')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.staff-assignments.index') }}">
            Staff Assignments
        </a>
    </li>
    <li class="breadcrumb-item active">Assign Screen</li>
@endsection

@push('css')
<style>
    .form-section-title {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 6px;
    }
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Assign Screen</h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.staff-assignments.store') }}"
              id="assignForm">
            @csrf

            <div class="row g-3">

                {{-- STAFF --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Staff</label>
                    <select name="user_id"
                            class="form-select @error('user_id') is-invalid @enderror"
                            required>
                        <option value="">Select Staff</option>
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- VENUE --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Venue</label>
                    <select id="venue"
                            name="venue_id"
                            class="form-select @error('venue_id') is-invalid @enderror"
                            required>
                        <option value="">Select Venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}"
                                {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('venue_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SCREEN --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Screen</label>
                    <select id="screen"
                            name="screen_id"
                            class="form-select @error('screen_id') is-invalid @enderror"
                            required>
                        <option value="">Select Screen</option>
                    </select>
                    @error('screen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.staff-assignments.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

                <button type="submit"
                        class="btn btn-primary"
                        id="submitBtn">
                    Assign Screen
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const venues = @json($venues);
    const venueSelect  = document.getElementById('venue');
    const screenSelect = document.getElementById('screen');
    const submitBtn    = document.getElementById('submitBtn');

    function loadScreens() {
        const venueId = venueSelect.value;
        screenSelect.innerHTML = '<option value="">Select Screen</option>';

        if (!venueId) return;

        const venue = venues.find(v => v.id == venueId);
        if (!venue || !venue.screens) return;

        venue.screens.forEach(screen => {
            if (screen.status && screen.status !== 'active') return;

            const option = document.createElement('option');
            option.value = screen.id;
            option.textContent = screen.name;
            screenSelect.appendChild(option);
        });

        @if(old('screen_id'))
            screenSelect.value = "{{ old('screen_id') }}";
        @endif
    }

    venueSelect.addEventListener('change', loadScreens);
    loadScreens();

    document.getElementById('assignForm')
        .addEventListener('submit', function () {
            submitBtn.disabled = true;
        });
});
</script>
@endpush
