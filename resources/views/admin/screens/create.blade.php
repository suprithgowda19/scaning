@extends('layouts.master')

@section('title', 'Add Screen')
@section('page_title', 'Add Screen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.screens.index') }}">Screens</a></li>
    <li class="breadcrumb-item active">Add Screen</li>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <form class="needs-validation" novalidate method="POST" action="{{ route('admin.screens.store') }}">
                    @csrf

                    <div class="row">

                        {{-- VENUE --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Select Venue <span class="text-danger">*</span></label>
                            <select name="venue_id"
                                    class="form-control @error('venue_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Venue --</option>

                                @foreach ($venues as $venue)
                                    <option value="{{ $venue->id }}"
                                        {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('venue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please select a venue.</div>
                            @enderror
                        </div>

                        {{-- SCREEN NAME --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Screen Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter Screen Name"
                                   value="{{ old('name') }}"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please enter screen name.</div>
                            @enderror
                        </div>

                        {{-- CAPACITY --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Capacity</label>
                            <input type="number"
                                   name="capacity"
                                   class="form-control @error('capacity') is-invalid @enderror"
                                   placeholder="Enter capacity (optional)"
                                   value="{{ old('capacity') }}">

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
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Please select status.</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary px-4 py-2" type="submit" style="border-radius: 12px;">
                            Submit
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
