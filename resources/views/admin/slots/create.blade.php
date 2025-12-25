@extends('layouts.master')

@section('title', 'Create Slot')
@section('page_title', 'Create Slot')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.slots.index') }}">Slots</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.slots.store') }}"
              class="needs-validation"
              novalidate>

            @csrf

            <div class="row g-4">

                {{-- START TIME --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Start Time <span class="text-danger">*</span>
                    </label>
                    <input type="time"
                           name="start_time"
                           value="{{ old('start_time') }}"
                           class="form-control @error('start_time') is-invalid @enderror"
                           required>

                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- END TIME --}}
                <div class="col-md-6">
                    <label class="form-label">
                        End Time <span class="text-danger">*</span>
                    </label>
                    <input type="time"
                           name="end_time"
                           value="{{ old('end_time') }}"
                           class="form-control @error('end_time') is-invalid @enderror"
                           required>

                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('admin.slots.index') }}"
                   class="btn btn-light">
                    <i data-feather="arrow-left" class="me-1"></i> Back
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Create Slot
                </button>

            </div>

        </form>

        <div class="alert alert-info mt-4">
            <strong>Note:</strong>
            Slots are <b>global time windows</b>.
            They are reused across all screens and venues.
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
