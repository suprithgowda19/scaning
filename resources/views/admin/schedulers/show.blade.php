@extends('layouts.master')

@section('title', 'View Scheduler')
@section('page_title', 'View Scheduler')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.schedulers.index') }}">Schedulers</a>
    </li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Venue</div>
            <div class="col-md-9">{{ $scheduler->venue->name }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Screen</div>
            <div class="col-md-9">{{ $scheduler->screen->name }}</div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Type</div>
            <div class="col-md-9">
                @if($scheduler->movie_title)
                    <span class="badge bg-primary">Movie</span>
                @else
                    <span class="badge bg-info">Event</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">
                {{ $scheduler->movie_title ? 'Movie Title' : 'Event Title' }}
            </div>
            <div class="col-md-9">
                {{ $scheduler->movie_title ?? $scheduler->event_title }}
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Language</div>
            <div class="col-md-9">
                {{ $scheduler->language ?? '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Duration</div>
            <div class="col-md-9">
                {{ $scheduler->duration ? $scheduler->duration.' mins' : '-' }}
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Show Date</div>
            <div class="col-md-9">
                {{ $scheduler->show_date->format('d-m-Y') }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Start Time</div>
            <div class="col-md-9">
                {{ $scheduler->start_time }}
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Status</div>
            <div class="col-md-9">
                @if($scheduler->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.schedulers.edit', $scheduler->id) }}"
               class="btn btn-primary">
                Edit
            </a>

            <a href="{{ route('admin.schedulers.index') }}"
               class="btn btn-secondary">
                Back
            </a>
        </div>

    </div>
</div>

@endsection
