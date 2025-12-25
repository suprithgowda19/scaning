@extends('layouts.master')

@section('title', 'Edit Slot')
@section('page_title', 'Edit Slot')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.slots.index') }}">Slots</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.slots.update', $slot->id) }}"
              method="POST"
              class="needs-validation"
              novalidate>

            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- START TIME --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Start Time <span class="text-danger">*</span>
                    </label>
                    <input type="time"
                           name="start_time"
                           value="{{ old('start_time', $slot->start_time) }}"
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
                           value="{{ old('end_time', $slot->end_time) }}"
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
                    Update Slot
                </button>

            </div>

        </form>

        <div class="alert alert-warning mt-4">
            <strong>Warning:</strong>
            Editing a slot affects <b>all shows</b> using this time window.
            Change only if you are certain.
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
