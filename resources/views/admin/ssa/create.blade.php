@extends('layouts.master')

@section('title', 'Assign Show')
@section('page_title', 'Assign Show')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.ssa.index') }}">Show Assignments</a></li>
    <li class="breadcrumb-item active">Create</li>
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

<div 
    x-data="ssaForm({
        venues: {{ $venues->toJson() }},
        slots: {{ $slots->toJson() }},
        taken: {{ $taken->toJson() }},
    })"
    class="row"
>
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assign New Show</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.ssa.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Venue --}}
                        <div class="col-md-4">
                            <label class="form-label form-section-title">Venue</label>
                            <select name="venue_id" class="form-select" x-model="venue_id" required>
                                <option value="">Select Venue</option>
                                <template x-for="venue in venues" :key="venue.id">
                                    <option :value="venue.id" x-text="venue.name"></option>
                                </template>
                            </select>
                            @error('venue_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Screen --}}
                        <div class="col-md-4">
                            <label class="form-label form-section-title">Screen</label>
                            <select name="screen_id" class="form-select" x-model="screen_id" required>
                                <option value="">Select Screen</option>

                                <template x-for="venue in venues" :key="'scr'+venue.id">
                                    <template x-if="venue.id == venue_id">
                                        <template x-for="screen in venue.screens" :key="screen.id">
                                            <option :value="screen.id" x-text="screen.name"></option>
                                        </template>
                                    </template>
                                </template>

                            </select>
                            @error('screen_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Day --}}
                        <div class="col-md-4">
                            <label class="form-label form-section-title">Day (1–7)</label>
                            <select name="day" class="form-select" x-model="day" required>
                                <option value="">Select Day</option>
                                <template x-for="d in 7" :key="'day'+d">
                                    <option :value="d" x-text="'Day ' + d"></option>
                                </template>
                            </select>
                            @error('day') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Slot --}}
                        <div class="col-md-6">
                            <label class="form-label form-section-title">Slot</label>
                            <select name="slot_id" class="form-select" required>

                                <option value="">Select Available Slot</option>

                                <template x-for="slot in availableSlots" :key="'slot'+slot.id">
                                    <option :value="slot.id" 
                                            x-text="formatTime(slot.start_time)">
                                    </option>
                                </template>

                            </select>
                            @error('slot_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Movie --}}
                        <div class="col-md-6">
                            <label class="form-label form-section-title">Movie</label>
                            <select name="movie_id" class="form-select" required>
                                <option value="">Select Movie</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->id }}">
                                        {{ $movie->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('movie_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.ssa.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Assign Show
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function ssaForm({ venues, slots, taken }) {
    return {
        venues,
        slots,
        taken,

        venue_id: '',
        screen_id: '',
        day: '',

        // Remove seconds from time and convert to AM/PM
        formatTime(timeStr) {
            const [h, m] = timeStr.split(':'); // ignore seconds
            const hour = parseInt(h);
            const suffix = hour >= 12 ? "PM" : "AM";
            const formattedHour = ((hour + 11) % 12 + 1);
            return `${formattedHour}:${m} ${suffix}`;
        },

        get availableSlots() {
            if (!this.venue_id || !this.screen_id || !this.day) return [];

            // 1) Slots for selected venue
            let venueSlots = this.slots.filter(s => s.venue_id == this.venue_id);

            // 2) Remove already used slots for selected screen + day
            let used = this.taken
                .filter(t => t.screen_id == this.screen_id && t.day == this.day)
                .map(t => t.slot_id);

            return venueSlots.filter(s => !used.includes(s.id));
        }
    }
}
</script>
@endpush
