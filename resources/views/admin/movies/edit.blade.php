@extends('layouts.master')

@section('title', 'Edit Movie')
@section('page_title', 'Edit Movie')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.movies.index') }}">Movies</a>
    </li>
    <li class="breadcrumb-item active">Edit Movie</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card">
            <div class="card-body">

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
                      action="{{ route('admin.movies.update', $movie->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Original Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="original_title"
                               class="form-control"
                               value="{{ old('original_title', $movie->original_title) }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">English Title</label>
                        <input type="text"
                               name="english_title"
                               class="form-control"
                               value="{{ old('english_title', $movie->english_title) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Language</label>
                        <input type="text"
                               name="language"
                               class="form-control"
                               value="{{ old('language', $movie->language) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Duration (minutes)</label>
                        <input type="number"
                               name="duration"
                               class="form-control"
                               value="{{ old('duration', $movie->duration) }}"
                               min="1">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.movies.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Movie
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
