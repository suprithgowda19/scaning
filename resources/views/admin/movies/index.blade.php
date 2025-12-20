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

    .icon-18 {
        width: 18px;
        height: 18px;
    }

    /* Zeta pagination style */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px;
        display: flex !important;
        justify-content: center !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 6px 14px !important;
        margin: 0 3px !important;
        background: #f0f2ff !important;
        border: none !important;
        color: #6c6c6c !important;
        font-weight: 500;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #6366f1 !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #6366f1 !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        background: #f7f7f7 !important;
        color: #bbb !important;
    }

    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 22px;
    }
    .switch input { display: none; }

    .slider {
        position: absolute;
        cursor: pointer;
        background-color: #dadada;
        border-radius: 34px;
        top: 0; left: 0; right: 0; bottom: 0;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px; width: 16px;
        left: 3px; bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked + .slider { background-color: #4caf50; }
    input:checked + .slider:before { transform: translateX(24px); }

</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"></h4>

    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary text-white">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Add Movie
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Title</th>
                <th>Language</th>
                <th>Duration</th>
                <th>Status</th>
                <th class="text-center" style="width:150px;">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($movies as $index => $movie)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $movie->title }}</td>

                    <td>{{ $movie->language }}</td>

                    <td>{{ $movie->duration ? $movie->duration . ' min' : '—' }}</td>

                    <td>
                        <label class="switch">
                            <input type="checkbox"
                                   class="toggle-status"
                                   data-id="{{ $movie->id }}"
                                   {{ $movie->status === 'active' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            {{-- VIEW --}}
                            <button class="btn btn-info btn-square text-white"
                                    onclick="window.location.href='{{ route('admin.movies.show', $movie->id) }}'"
                                    title="View">
                                <i data-feather="eye" class="icon-18"></i>
                            </button>

                            {{-- EDIT --}}
                            <button class="btn btn-primary btn-square"
                                    onclick="window.location.href='{{ route('admin.movies.edit', $movie->id) }}'"
                                    title="Edit">
                                <i data-feather="edit" class="icon-18"></i>
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.movies.destroy', $movie->id) }}"
                                  method="POST"
                                  class="delete-form">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-square delete-btn"
                                        title="Delete">
                                    <i data-feather="trash-2" class="icon-18"></i>
                                </button>
                            </form>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    feather.replace();

    // DELETE CONFIRMATION
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let form = this.closest("form");

            Swal.fire({
                title: "Delete Movie?",
                text: "This movie will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it"
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // STATUS UPDATE AJAX
    document.querySelectorAll(".toggle-status").forEach(el => {
        el.addEventListener("change", function () {
            let id = this.dataset.id;
            let status = this.checked ? "active" : "inactive";

            fetch("{{ route('admin.movies.toggle-status') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id, status })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: "success",
                    title: "Updated",
                    text: `Movie is now ${data.status.toUpperCase()}`,
                    timer: 1200,
                    showConfirmButton: false
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: "Could not update status."
                });
                this.checked = !this.checked;
            });
        });
    });

    // SUCCESS POPUP
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
