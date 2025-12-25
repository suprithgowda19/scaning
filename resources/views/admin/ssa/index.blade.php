@extends('layouts.master')

@section('title', 'Shows')
@section('page_title', 'Show Schedule')

@section('breadcrumb')
    <li class="breadcrumb-item">Shows</li>
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

        .icon-18 {
            width: 18px;
            height: 18px;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
@endpush

@section('content')

    <div class="mb-3">
        <span class="text-muted">
            Shows are imported from scheduler. You may edit a show to swap movies or move screens.
        </span>
    </div>

    <div class="table-responsive">
        <table class="display" id="ssaTable" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Screen</th>
                    <th>Time</th>
                    <th>Movie</th>
                    <th class="text-center" style="width:120px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($shows as $index => $ssa)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($ssa->show_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $ssa->screen->venue->name }}
                        </td>

                        <td>
                            {{ $ssa->screen->name }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($ssa->slot->start_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($ssa->slot->end_time)->format('h:i A') }}
                        </td>

                        <td>
                            <strong>{{ $ssa->movie->title }}</strong>
                            @if ($ssa->movie->language)
                                <div class="meta">
                                    {{ $ssa->movie->language }}
                                </div>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                {{-- VIEW --}}
                                <a href="{{ route('admin.ssa.show', $ssa->id) }}" class="btn btn-info btn-square"
                                    title="View Show">
                                    <i data-feather="eye" class="icon-18"></i>
                                </a>

                                {{-- EDIT / SWAP --}}
                                <a href="{{ route('admin.ssa.edit', $ssa->id) }}" class="btn btn-primary btn-square"
                                    title="Edit / Swap">
                                    <i data-feather="edit" class="icon-18"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No shows found.
                        </td>
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
        document.addEventListener("DOMContentLoaded", function() {
            feather.replace();

            $('#ssaTable').DataTable({
                pagingType: "simple_numbers",
                order: [
                    [1, 'asc'],
                    [4, 'asc']
                ]
            });

            @if (session('success'))
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "{{ session('success') }}",
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush
