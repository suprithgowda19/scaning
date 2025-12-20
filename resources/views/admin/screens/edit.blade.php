@extends('layouts.master')

@section('title', 'Edit Screen')
@section('page_title', 'Edit Screen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.screens.index') }}">Screens</a></li>
    <li class="breadcrumb-item active">Edit Screen</li>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate
                      method="POST"
                      action="{{ route('admin.screens.update', $screen->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- VENUE (locked during editing) --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Venue <span class="text-danger">*</span></label>
                            <select class="form-control" disabled>
                                <option>{{ $screen->venue->name }}</option>
                            </select>

                            {{-- Pass venue_id hidden --}}
                            <input type="hidden" name="venue_id" value="{{ $screen->venue_id }}">
                        </div>

                        {{-- SCREEN NAME --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Screen Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $screen->name) }}"
                                   placeholder="Enter Screen Name"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Screen name is required.</div>
                            @enderror
                        </div>

                        {{-- CAPACITY --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Capacity</label>
                            <input type="number"
                                   name="capacity"
                                   class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', $screen->capacity) }}"
                                   placeholder="Enter capacity (optional)">

                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="active"   {{ old('status', $screen->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $screen->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please choose status.</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary px-4 py-2" type="submit" style="border-radius: 12px;">
                            Update
                        </button>
                        <a href="{{ route('admin.screens.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 12px;">
                            Cancel
                        </a>
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

        const form = document.querySelector("form.needs-validation");

        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        });

        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#3085d6"
            });
        @endif

    });
</script>
@endpush
