@extends('layouts.master')

@section('title', 'Edit Movie')
@section('page_title', 'Edit Movie')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.movies.index') }}">Movies</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.movies.update', $movie->id) }}"
              method="POST"
              class="needs-validation"
              novalidate>

            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- TITLE --}}
                <div class="col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $movie->title) }}"
                           class="form-control @error('title') is-invalid @enderror"
                           required>

                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ORIGINAL TITLE --}}
                <div class="col-md-6">
                    <label class="form-label">Original Title</label>
                    <input type="text"
                           name="original_title"
                           value="{{ old('original_title', $movie->original_title) }}"
                           class="form-control @error('original_title') is-invalid @enderror">

                    @error('original_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- LANGUAGE --}}
                <div class="col-md-6">
                    <label class="form-label">Language</label>
                    <select name="language"
                            class="form-control @error('language') is-invalid @enderror">
                        <option value="">—</option>
                        @foreach ($languages as $lang)
                            <option value="{{ $lang }}"
                                {{ old('language', $movie->language) === $lang ? 'selected' : '' }}>
                                {{ $lang }}
                            </option>
                        @endforeach
                    </select>

                    @error('language')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DURATION --}}
                <div class="col-md-6">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number"
                           name="duration"
                           min="1"
                           value="{{ old('duration', $movie->duration) }}"
                           class="form-control @error('duration') is-invalid @enderror">

                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input type="text"
                           name="category"
                           value="{{ old('category', $movie->category) }}"
                           class="form-control @error('category') is-invalid @enderror">

                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DIRECTOR --}}
                <div class="col-md-6">
                    <label class="form-label">Director</label>
                    <input type="text"
                           name="director"
                           value="{{ old('director', $movie->director) }}"
                           class="form-control @error('director') is-invalid @enderror">

                    @error('director')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- COUNTRY --}}
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text"
                           name="country"
                           value="{{ old('country', $movie->country) }}"
                           class="form-control @error('country') is-invalid @enderror">

                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- YEAR --}}
                <div class="col-md-6">
                    <label class="form-label">Year</label>
                    <input type="text"
                           name="year"
                           value="{{ old('year', $movie->year) }}"
                           class="form-control @error('year') is-invalid @enderror">

                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EXTERNAL FILM ID (READ ONLY) --}}
                <div class="col-md-12">
                    <label class="form-label">External Film ID</label>
                    <input type="text"
                           value="{{ $movie->external_film_id }}"
                           class="form-control"
                           disabled>
                    <small class="text-muted">
                        Imported from scheduler (read-only)
                    </small>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.movies.index') }}" class="btn btn-light">
                    <i data-feather="arrow-left" class="me-1"></i> Back
                </a>

                <button type="submit" class="btn btn-primary">
                    Update Movie
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
