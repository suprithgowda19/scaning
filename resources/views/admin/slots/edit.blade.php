@extends('layouts.master')

@section('title', 'Edit Slot')
@section('page_title', 'Edit Slot')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.slots.index') }}">Slots</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Slot</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.slots.update', $slot->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Venue --}}
                        <div class="col-md-6">
                            <label class="form-label">Venue</label>
                            <select name="venue_id" class="form-select" required>
                                <option value="">Select Venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}"
                                        {{ old('venue_id', $slot->venue_id) == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>

                            <input type="time"
                                   name="start_time"
                                   class="form-control"
                                   value="{{ old('start_time', \Carbon\Carbon::parse($slot->start_time)->format('H:i')) }}"
                                   required>

                            @error('start_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.slots.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Slot
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
