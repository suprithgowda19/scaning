@extends('layouts.master')

@section('title', 'Movies')
@section('page_title', 'Movies')

@section('breadcrumb')
    <li class="breadcrumb-item">Movies</li>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <style>
        .btn-square {
            width: 38px;
            height: 38px;
            padding: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px !important;
        }
        .icon-18 { width: 18px; height: 18px; }
    </style>
@endpush

@section('content')

{{-- Header actions --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    {{-- Import --}}
    <form method="POST"
          action="{{ route('admin.movies.import') }}"
          enctype="multipart/form-data"
          class="d-flex gap-2 align-items-center">
        @csrf

        <input type="file"
               name="file"
               class="form-control form-control-sm"
               accept=".csv,.xlsx,.xls"
               required>

        <button type="submit" class="btn btn-success btn-sm">
            <i data-feather="upload" class="icon-18 me-1"></i> Import
        </button>
    </form>

    {{-- Add --}}
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Movie
    </a>
</div>

{{-- Success message --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Validation errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Movies table --}}
<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Original Title</th>
                <th>English Title</th>
                <th>Language</th>
                <th>Duration (min)</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($movies as $index => $movie)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $movie->original_title ?: '-' }}</td>
                    <td>{{ $movie->eng_title ?: '-' }}</td>
                    <td>{{ $movie->language ?: '-' }}</td>
                    <td>{{ $movie->duration ?: '-' }}</td>

                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.movies.edit', $movie->id) }}"
                           class="btn btn-primary btn-square"
                           title="Edit">
                            <i data-feather="edit" class="icon-18"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

    <script>
        feather.replace();
    </script>
@endpush
