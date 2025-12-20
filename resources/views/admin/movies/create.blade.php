@extends('layouts.master')

@section('title', 'Add Movie')
@section('page_title', 'Add Movie')

@section('breadcrumb')
    <li class="breadcrumb-item">Movies</li>
@endsection

@push('css')
<style>
    .poster-preview {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ccc;
        display: none;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">

            <div class="card-body">

                <form id="movieForm" class="needs-validation" novalidate 
                      method="POST" enctype="multipart/form-data"
                      action="{{ route('admin.movies.store') }}">
                    @csrf

                    <div class="row">

                        {{-- Movie Title --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Movie Title *</label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Enter movie title"
                                   required>

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Movie title is required.</div>
                            @enderror
                        </div>

                        {{-- Language --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Language *</label>
                            <select name="language"
                                    class="form-control @error('language') is-invalid @enderror"
                                    required>
                                <option value="">Select language</option>
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang }}" {{ old('language') == $lang ? 'selected' : '' }}>
                                        {{ $lang }}
                                    </option>
                                @endforeach
                            </select>

                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Select a language.</div>
                            @enderror
                        </div>

                        {{-- Duration --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Duration (minutes)</label>
                            <input type="number"
                                   class="form-control @error('duration') is-invalid @enderror"
                                   name="duration"
                                   value="{{ old('duration') }}"
                                   placeholder="Example: 120">

                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status *</label>
                            <select name="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="active"  {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Status is required.</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Optional description">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Poster Upload --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Poster Image</label>
                            <input type="file"
                                   name="poster"
                                   accept="image/*"
                                   class="form-control @error('poster') is-invalid @enderror"
                                   id="posterInput">

                            @error('poster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <img id="posterPreview" class="poster-preview mt-2">
                        </div>

                    </div>

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary" type="submit" style="border-radius:12px;">
                            Submit
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // FORM VALIDATION + SWEET ALERT CONFIRMATION
    const form = document.getElementById("movieForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (form.checkValidity()) {

            Swal.fire({
                title: "Confirm Submission",
                text: "Are you sure you want to add this movie?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Submit"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        }

        form.classList.add("was-validated");
    });

    // POSTER PREVIEW
    document.getElementById("posterInput").addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById("posterPreview");
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        }
    });

});
</script>
@endpush
