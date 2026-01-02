@extends('layouts.master')

@section('title', 'Edit Scheduler')
@section('page_title', 'Edit Scheduler')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.schedulers.index') }}">Schedulers</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('admin.schedulers.update', $scheduler->id) }}">
    @csrf
    @method('PUT')

    {{-- Venue / Screen --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Venue Name</label>
            <input type="text"
                   name="venue_name"
                   class="form-control"
                   value="{{ old('venue_name', $scheduler->venue->name) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Screen Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="screen_name"
                   class="form-control"
                   required
                   value="{{ old('screen_name', $scheduler->screen->name) }}">
        </div>
    </div>

    <hr>

    {{-- Movie / Event --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Movie Title</label>
            <input type="text"
                   name="movie_title"
                   class="form-control"
                   value="{{ old('movie_title', $scheduler->movie_title) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Event Title</label>
            <input type="text"
                   name="event_title"
                   class="form-control"
                   value="{{ old('event_title', $scheduler->event_title) }}">
        </div>
    </div>

    {{-- Date / Time / Status --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">Show Date</label>
            <input type="date"
                   name="show_date"
                   class="form-control"
                   value="{{ old('show_date', $scheduler->show_date->format('Y-m-d')) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Start Time</label>
            <input type="time"
                   name="start_time"
                   class="form-control"
                   value="{{ old('start_time', $scheduler->start_time) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $scheduler->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ ! $scheduler->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.schedulers.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection
