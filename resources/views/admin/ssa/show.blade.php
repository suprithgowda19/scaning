@extends('layouts.master')

@section('title', 'Show Assignment')
@section('page_title', 'Show Assignment')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.ssa.index') }}">Show Assignments</a>
    </li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Show Details</h5>
                </div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Venue</div>
                        <div class="col-sm-8">{{ $ssa->venue->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Screen</div>
                        <div class="col-sm-8">{{ $ssa->screen->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Day</div>
                        <div class="col-sm-8">Day {{ $ssa->day }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Slot Time</div>
                        <div class="col-sm-8">
                            {{ \Carbon\Carbon::parse($ssa->slot->start_time)->format('h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Movie</div>
                        <div class="col-sm-8">{{ $ssa->movie->title }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Status</div>
                        <div class="col-sm-8">
                            @if ($ssa->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Created At</div>
                        <div class="col-sm-8">
                            {{ $ssa->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-4 fw-bold">Last Updated</div>
                        <div class="col-sm-8">
                            {{ $ssa->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.ssa.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <a href="{{ route('admin.ssa.edit', $ssa->id) }}" class="btn btn-primary">
                            Edit
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
