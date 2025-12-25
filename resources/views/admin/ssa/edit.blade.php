@extends('layouts.master')

@section('title', 'Edit Show')
@section('page_title', 'Edit Show')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.ssa.index') }}">Shows</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Show (Swap / Move)</h5>
            </div>

            <div class="card-body">

                {{-- READ-ONLY CONTEXT --}}
                <div class="alert alert-light mb-4">
                    <div><strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($ssa->show_date)->format('d M Y') }}
                    </div>
                    <div><strong>Time:</strong>
                        {{ \Carbon\Carbon::parse($ssa->slot->start_time)->format('h:i A') }}
                        –
                        {{ \Carbon\Carbon::parse($ssa->slot->end_time)->format('h:i A') }}
                    </div>
                    <div><strong>Venue:</strong>
                        {{ $ssa->screen->venue->name }}
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('admin.ssa.update', $ssa->id) }}"
                      class="needs-validation"
                      novalidate>

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- SCREEN --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Screen <span class="text-danger">*</span>
                            </label>
                            <select name="screen_id"
                                    class="form-select @error('screen_id') is-invalid @enderror"
                                    required>
                                @foreach ($screens as $screen)
                                    <option value="{{ $screen->id }}"
                                        {{ $screen->id === $ssa->screen_id ? 'selected' : '' }}>
                                        {{ $screen->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('screen_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MOVIE --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Movie <span class="text-danger">*</span>
                            </label>
                            <select name="movie_id"
                                    class="form-select @error('movie_id') is-invalid @enderror"
                                    required>
                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}"
                                        {{ $movie->id === $ssa->movie_id ? 'selected' : '' }}>
                                        {{ $movie->title }}
                                        @if($movie->language)
                                            ({{ $movie->language }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('movie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- WARNING --}}
                    <div class="alert alert-warning mt-4">
                        <strong>Warning:</strong>
                        Changing the screen or movie will affect staff scanning
                        and attendee entry for this show.
                    </div>

                    {{-- ACTIONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.ssa.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
