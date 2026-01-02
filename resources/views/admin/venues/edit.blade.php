@extends('layouts.master')

@section('title', 'Edit Venue')
@section('page_title', 'Edit Venue')

@section('breadcrumb')
    <li class="breadcrumb-item">Venues</li>
    <li class="breadcrumb-item active">Edit Venue</li>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <form class="needs-validation"
                      novalidate
                      method="POST"
                      action="{{ route('admin.venues.update', $venue->id) }}"
                      id="editVenueForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">

                            {{-- VENUE NAME --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Venue Name <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ old('name', $venue->name) }}"
                                       placeholder="Enter venue name"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Venue name is required.</div>
                                @enderror
                            </div>

                       
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button class="btn btn-primary" type="submit" style="border-radius: 12px;">
                            Update Venue
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
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("editVenueForm");

    if (!form) return;

    form.addEventListener("submit", function(event) {
        event.preventDefault();
        event.stopPropagation();

        if (form.checkValidity()) {
            Swal.fire({
                title: "Confirm Update",
                text: "Are you sure you want to update this venue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
