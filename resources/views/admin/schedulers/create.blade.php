@extends('layouts.master')

@section('title', 'Add Scheduler')
@section('page_title', 'Add Scheduler')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.schedulers.index') }}">Schedulers</a>
    </li>
    <li class="breadcrumb-item active">Add</li>
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

<form method="POST" action="{{ route('admin.schedulers.store') }}">
    @csrf

    {{-- Venue / Screen --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Venue Name</label>
            <input type="text"
                   name="venue_name"
                   class="form-control"
                   value="{{ old('venue_name') }}">
            <small class="text-muted">Leave empty if only one venue exists</small>
        </div>

        <div class="col-md-4">
            <label class="form-label">Screen Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="screen_name"
                   class="form-control"
                   required
                   value="{{ old('screen_name') }}">
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
                   value="{{ old('movie_title') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Event Title</label>
            <input type="text"
                   name="event_title"
                   class="form-control"
                   value="{{ old('event_title') }}">
            <small class="text-muted">
                Fill either Movie OR Event (not both)
            </small>
        </div>
    </div>

    {{-- Date / Time --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">Show Date <span class="text-danger">*</span></label>
            <input type="date"
                   name="show_date"
                   class="form-control"
                   required
                   value="{{ old('show_date') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Start Time <span class="text-danger">*</span></label>
            <input type="time"
                   name="start_time"
                   class="form-control"
                   required
                   value="{{ old('start_time') }}">
        </div>
    </div>

    <button class="btn btn-primary">Save</button>
    <a href="{{ route('admin.schedulers.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection
