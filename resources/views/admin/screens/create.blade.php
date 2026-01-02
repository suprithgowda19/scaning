@extends('layouts.master')

@section('title', 'Add Screen')
@section('page_title', 'Add Screen')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.screens.index') }}">Screens</a>
    </li>
    <li class="breadcrumb-item active">Add Screen</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.screens.store') }}" method="POST">
            @csrf

            {{-- Venue --}}
            <div class="mb-3">
                <label class="form-label">Venue <span class="text-danger">*</span></label>
                <select name="venue_id"
                        class="form-select @error('venue_id') is-invalid @enderror"
                        required>
                    <option value="">Select Venue</option>
                    @foreach ($venues as $venue)
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

            {{-- Screen Name --}}
            <div class="mb-3">
                <label class="form-label">Screen Name <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="e.g. Screen 1, Audi A"
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
                       value="{{ old('capacity') }}"
                       min="1"
                       placeholder="Total seats"
                       required>
                @error('capacity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Save Screen
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
