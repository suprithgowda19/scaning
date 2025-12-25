@extends('layouts.master')

@section('title', 'View Movie')
@section('page_title', 'Movie Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.movies.index') }}">Movies</a>
    </li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('css')
<style>
    .movie-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 4px;
    }

    .movie-value {
        font-size: 15px;
        color: #222;
    }

    .meta-box {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fafafa;
    }
</style>
@endpush

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.movies.index') }}" class="btn btn-light">
        <i data-feather="arrow-left" class="me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            {{-- LEFT: Core Titles --}}
            <div class="col-md-6">

                <div class="meta-box">
                    <div class="movie-label">Title</div>
                    <div class="movie-value">{{ $movie->title }}</div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">Original Title</div>
                    <div class="movie-value">
                        {{ $movie->original_title ?? '—' }}
                    </div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">Language</div>
                    <div class="movie-value">
                        {{ $movie->language ?? '—' }}
                    </div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">Duration</div>
                    <div class="movie-value">
                        {{ $movie->duration ? $movie->duration.' min' : '—' }}
                    </div>
                </div>

            </div>

            {{-- RIGHT: Metadata --}}
            <div class="col-md-6">

                <div class="meta-box">
                    <div class="movie-label">Category</div>
                    <div class="movie-value">
                        {{ $movie->category ?? '—' }}
                    </div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">Director</div>
                    <div class="movie-value">
                        {{ $movie->director ?? '—' }}
                    </div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">Country / Year</div>
                    <div class="movie-value">
                        {{ $movie->country ?? '—' }}
                        {{ $movie->year ? ' / '.$movie->year : '' }}
                    </div>
                </div>

                <div class="meta-box">
                    <div class="movie-label">External Film ID</div>
                    <div class="movie-value text-muted">
                        {{ $movie->external_film_id ?? '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
