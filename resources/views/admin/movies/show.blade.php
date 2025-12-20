@extends('layouts.master')

@section('title', 'View Movie')
@section('page_title', 'Movie Details')

@section('breadcrumb')
    <li class="breadcrumb-item">Movies</li>
    <li class="breadcrumb-item active">View</li>
@endsection

@push('css')
<style>
    .movie-poster {
        width: 180px;
        height: 260px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .movie-label {
        font-weight: 600;
        color: #555;
    }

    .movie-value {
        font-size: 16px;
        color: #222;
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

                {{-- LEFT SIDE: Poster --}}
                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <img src="{{ $movie->poster ? asset('storage/'.$movie->poster) : asset('assets/images/no-poster.png') }}"
                         class="movie-poster">
                </div>

                {{-- RIGHT SIDE: Details --}}
                <div class="col-md-8">

                    <div class="mb-3">
                        <div class="movie-label">Title</div>
                        <div class="movie-value">{{ $movie->title }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="movie-label">Language</div>
                        <div class="movie-value">{{ $movie->language }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="movie-label">Duration</div>
                        <div class="movie-value">
                            {{ $movie->duration ? $movie->duration.' min' : '—' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="movie-label">Description</div>
                        <div class="movie-value">
                            {{ $movie->description ?? 'No description provided.' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="movie-label">Status</div>
                        <span class="badge {{ $movie->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($movie->status) }}
                        </span>
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
