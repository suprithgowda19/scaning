@extends('layouts.master')

@section('title', 'Show Details')
@section('page_title', 'Show Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.ssa.index') }}">Shows</a>
    </li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('css')
<style>
    .section {
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 10px;
    }

    .movie-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .meta {
        font-size: 14px;
        color: #6b7280;
    }

    .kv {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .kv:last-child {
        border-bottom: none;
    }

    .kv-label {
        color: #6b7280;
        font-weight: 500;
    }

    .kv-value {
        color: #111827;
        font-weight: 500;
        text-align: right;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">

        <div class="card">
            <div class="card-body">

                {{-- MOVIE --}}
                <div class="section">
                    <div class="movie-title">
                        {{ $ssa->movie->title }}
                    </div>

                    <div class="meta mt-1">
                        {{ $ssa->movie->language ?? '—' }}
                    </div>
                </div>

                {{-- SCHEDULE --}}
                <div class="section">
                    <div class="section-title">Schedule</div>

                    <div class="kv">
                        <div class="kv-label">Date</div>
                        <div class="kv-value">
                            {{ \Carbon\Carbon::parse($ssa->show_date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="kv">
                        <div class="kv-label">Time</div>
                        <div class="kv-value">
                            {{ \Carbon\Carbon::parse($ssa->slot->start_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($ssa->slot->end_time)->format('h:i A') }}
                        </div>
                    </div>

                    <div class="kv">
                        <div class="kv-label">Venue</div>
                        <div class="kv-value">
                            {{ $ssa->screen->venue->name }}
                        </div>
                    </div>

                    <div class="kv">
                        <div class="kv-label">Screen</div>
                        <div class="kv-value">
                            {{ $ssa->screen->name }}
                        </div>
                    </div>
                </div>

                {{-- SYSTEM --}}
                <div class="section">
                    <div class="section-title">System</div>

                    <div class="kv">
                        <div class="kv-label">Created</div>
                        <div class="kv-value">
                            {{ $ssa->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="kv">
                        <div class="kv-label">Last Updated</div>
                        <div class="kv-value">
                            {{ $ssa->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.ssa.index') }}" class="btn btn-light">
                        Back
                    </a>

                    <a href="{{ route('admin.ssa.edit', $ssa->id) }}" class="btn btn-primary">
                        Edit / Swap Movie
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
