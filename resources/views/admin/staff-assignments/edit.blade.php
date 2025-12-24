@extends('layouts.master')

@section('title', 'Edit Assignment')
@section('page_title', 'Edit Staff → Screen Assignment')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.staff-assignments.index') }}">Staff Assignments</a>
    </li>
    <li class="breadcrumb-item active">Edit Assignment</li>
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
        <h5 class="mb-0">Edit Assignment</h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.staff-assignments.update', $assignment->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- STAFF --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Staff</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}"
                                {{ $assignment->user_id == $user->id ? 'selected' : '' }}>
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
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}"
                                {{ $assignment->venue_id == $venue->id ? 'selected' : '' }}>
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
                        @foreach(
                            optional($venues->firstWhere('id', $assignment->venue_id))->screens ?? []
                            as $screen
                        )
                            <option value="{{ $screen->id }}"
                                {{ $assignment->screen_id == $screen->id ? 'selected' : '' }}>
                                {{ $screen->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('screen_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="col-md-4">
                    <label class="form-label form-section-title">Status</label>
                    <select name="active" class="form-select" required>
                        <option value="1" {{ $assignment->active ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ !$assignment->active ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                    @error('active')
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
                    Update Assignment
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
    const currentScreenId = "{{ $assignment->screen_id }}";

    function loadScreens() {
        const venueId = venueSelect.value;
        screenSelect.innerHTML = '<option value="">Select Screen</option>';

        const venue = venues.find(v => v.id == venueId);
        if (!venue || !venue.screens) return;

        venue.screens.forEach(screen => {
            const option = document.createElement('option');
            option.value = screen.id;
            option.textContent = screen.name;
            screenSelect.appendChild(option);
        });

        screenSelect.value = currentScreenId;
    }

    venueSelect.addEventListener('change', loadScreens);
    loadScreens();
});
</script>
@endpush
