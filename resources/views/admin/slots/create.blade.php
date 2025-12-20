@extends('layouts.master')

@section('title', 'Create Slot')
@section('page_title', 'Create Slot')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.slots.index') }}">Slots</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.slots.store') }}">
                    @csrf

                    <div class="row g-3">

                        <!-- Venue -->
                        <div class="col-md-6">
                            <label for="venue_id" class="form-label">Venue</label>
                            <select name="venue_id" id="venue_id" class="form-select" required>
                                <option value="">Select Venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}"
                                        {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time"
                                   name="start_time"
                                   id="start_time"
                                   class="form-control"
                                   value="{{ old('start_time') }}"
                                   required>
                            @error('start_time')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.slots.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Create Slot
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
