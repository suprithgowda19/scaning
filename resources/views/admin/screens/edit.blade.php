@extends('layouts.master')

@section('title', 'Edit Screen')
@section('page_title', 'Edit Screen')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.screens.index') }}">Screens</a>
    </li>
    <li class="breadcrumb-item active">Edit Screen</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.screens.update', $screen->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Venue --}}
            <div class="mb-3">
                <label class="form-label">Venue <span class="text-danger">*</span></label>
                <select name="venue_id"
                        class="form-select @error('venue_id') is-invalid @enderror"
                        required>
                    @foreach ($venues as $venue)
                        <option value="{{ $venue->id }}"
                            {{ old('venue_id', $screen->venue_id) == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Screen Name --}}
            <div class="mb-3">
                <label class="form-label">Screen Name <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $screen->name) }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Capacity --}}
            <div class="mb-3">
                <label class="form-label">Capacity <span class="text-danger">*</span></label>
                <input type="number"
                       name="capacity"
                       class="form-control @error('capacity') is-invalid @enderror"
                       value="{{ old('capacity', $screen->capacity) }}"
                       min="1"
                       required>
                @error('capacity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Update Screen
                </button>

                <a href="{{ route('admin.screens.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
