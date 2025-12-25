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
        width: 40px;
        height: 40px;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px !important;
    }
    .icon-18 { width: 18px; height: 18px; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Movies</h4>

    {{-- INFO ONLY --}}
    <span class="text-muted small">
        Movies are imported from scheduler and editable for metadata only
    </span>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Language</th>
                <th>Duration</th>
                <th>Category</th>
                <th class="text-center" style="width:120px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($movies as $index => $movie)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        <strong>{{ $movie->title }}</strong>
                        @if($movie->original_title)
                            <div class="text-muted small">
                                {{ $movie->original_title }}
                            </div>
                        @endif
                    </td>

                    <td>{{ $movie->language ?? '—' }}</td>

                    <td>
                        {{ $movie->duration ? $movie->duration . ' min' : '—' }}
                    </td>

                    <td>{{ $movie->category ?? '—' }}</td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- VIEW --}}
                            <a href="{{ route('admin.movies.show', $movie->id) }}"
                               class="btn btn-info btn-square text-white"
                               title="View">
                                <i data-feather="eye" class="icon-18"></i>
                            </a>

                            {{-- EDIT --}}
                            <a href="{{ route('admin.movies.edit', $movie->id) }}"
                               class="btn btn-primary btn-square"
                               title="Edit">
                                <i data-feather="edit" class="icon-18"></i>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No movies found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    feather.replace();
});
</script>
@endpush
