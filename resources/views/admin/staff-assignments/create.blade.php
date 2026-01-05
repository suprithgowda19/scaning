@extends('layouts.master')

@section('title', 'Assign Screen')
@section('page_title', 'Assign Staff → Screen')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.staff-assignments.index') }}">Staff Assignments</a>
    </li>
    <li class="breadcrumb-item active">Assign</li>
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
        <h5 class="mb-0">Assign Staff to Screen</h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.staff-assignments.store') }}">
            @csrf

            <div class="row g-3">

                {{-- STAFF --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Staff</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select Staff</option>
                        @foreach ($staff as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- VENUE --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Venue</label>
                    <select id="venue" name="venue_id" class="form-select" required>
                        <option value="">Select Venue</option>
                        @foreach ($venues as $venue)
                            <option value="{{ $venue->id }}"
                                {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('venue_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- SCREEN --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Screen</label>
                    <select id="screen" name="screen_id" class="form-select" required>
                        <option value="">Select Screen</option>
                    </select>
                    @error('screen_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.staff-assignments.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

                <button type="submit" class="btn btn-primary">
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

    // 🔑 Values restored after validation failure
    const selectedVenueId  = "{{ old('venue_id') }}";
    const selectedScreenId = "{{ old('screen_id') }}";

    function loadScreens(restoreSelected = false) {
        screenSelect.innerHTML = '<option value="">Select Screen</option>';

        const venue = venues.find(v => v.id == venueSelect.value);
        if (!venue || !venue.screens) return;

        venue.screens.forEach(screen => {
            const option = document.createElement('option');
            option.value = screen.id;
            option.textContent = screen.name;
            screenSelect.appendChild(option);
        });

        // Restore previously selected screen
        if (restoreSelected && selectedScreenId) {
            screenSelect.value = selectedScreenId;
        }
    }

    venueSelect.addEventListener('change', function () {
        loadScreens(false);
    });

    // 🔑 Auto-run on page load if validation failed
    if (selectedVenueId) {
        venueSelect.value = selectedVenueId;
        loadScreens(true);
    }
});
</script>
@endpush
