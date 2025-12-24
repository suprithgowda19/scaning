@extends('layouts.master')

@section('title', 'Users')
@section('page_title', 'Users')

@section('breadcrumb')
    <li class="breadcrumb-item">Users</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/vendors/datatables.css') }}">
<style>
    .btn-square {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
    .icon-18 { width: 18px; height: 18px; }
    tr.inactive { opacity: 0.55; }

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
        inset: 0;
        transition: .4s;
    }
    .slider:before {
        content: "";
        position: absolute;
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .4s;
    }
    input:checked + .slider { background-color: #4caf50; }
    input:checked + .slider:before { transform: translateX(24px); }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i data-feather="plus-circle" class="icon-18 me-1"></i> Add User
    </a>
</div>

<div class="table-responsive">
    <table class="display" id="data-source-1" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role(s)</th>
                <th>Status</th>
                <th class="text-center" style="width:180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $i => $user)
                <tr class="{{ !$user->active ? 'inactive' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{ $user->getRoleNames()->implode(', ') ?: '—' }}
                        <small class="text-muted d-block">read-only</small>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox"
                                   class="toggle-status"
                                   data-id="{{ $user->id }}"
                                   {{ $user->active ? 'checked' : '' }}
                                   {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-info btn-square text-white">
                                <i data-feather="eye" class="icon-18"></i>
                            </a>

                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-primary btn-square">
                                <i data-feather="edit" class="icon-18"></i>
                            </a>

                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="btn btn-danger btn-square delete-btn">
                                        <i data-feather="trash-2" class="icon-18"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    feather.replace();

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = btn.closest('form');
            Swal.fire({
                title: 'Deactivate user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, deactivate'
            }).then(r => r.isConfirmed && form.submit());
        });
    });

    document.querySelectorAll('.toggle-status').forEach(el => {
        let prev = el.checked;

        el.addEventListener('change', () => {
            if (el.disabled) {
                el.checked = prev;
                return;
            }

            Swal.fire({
                title: el.checked ? 'Activate user?' : 'Deactivate user?',
                icon: el.checked ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm'
            }).then(r => {
                if (!r.isConfirmed) {
                    el.checked = prev;
                    return;
                }

                fetch("{{ route('admin.users.toggle-status') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: el.dataset.id,
                        active: el.checked ? 1 : 0
                    })
                }).then(() => prev = el.checked)
                  .catch(() => el.checked = prev);
            });
        });
    });
});
</script>
@endpush
