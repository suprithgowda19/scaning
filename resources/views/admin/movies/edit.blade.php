@extends('layouts.master')

@section('title', 'Edit Movie')
@section('page_title', 'Edit Movie')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">Movies</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('css')
    <style>
        .poster-preview {
            width: 140px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
@endpush

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>

                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- TITLE --}}
                    <div class="col-md-6">
                        <label class="form-label">Movie Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $movie->title) }}"
                            class="form-control @error('title') is-invalid @enderror" required>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- LANGUAGE --}}
                    <div class="col-md-6">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <select name="language" class="form-control @error('language') is-invalid @enderror" required>
                            <option value="">Select Language</option>

                            @foreach (['Kannada', 'Hindi', 'English', 'Tamil', 'Telugu', 'Malayalam'] as $lang)
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
                        <input type="number" name="duration" min="1" value="{{ old('duration', $movie->duration) }}"
                            class="form-control @error('duration') is-invalid @enderror">

                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $movie->status) === 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $movie->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $movie->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- POSTER IMAGE --}}
                    <div class="col-md-12">
                        <label class="form-label">Poster (optional)</label>
                        <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror">

                        @error('poster')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($movie->poster)
                            <div class="mt-3">
                                <p class="fw-bold">Current Poster:</p>
                                <img src="{{ asset('storage/' . $movie->poster) }}" class="poster-preview">
                            </div>
                        @endif
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">

                    {{-- BACK BUTTON --}}
                    <a href="{{ route('admin.movies.index') }}" class="btn btn-light">
                        <i data-feather="arrow-left" class="me-1"></i> Back
                    </a>

                    {{-- SAVE BUTTON --}}
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

        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@endpush
