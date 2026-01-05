@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>User Details</h4>
    </div>

    <div class="card-body">
        <table class="table table-bordered align-middle">
            {{-- BASIC DETAILS --}}
            <tr>
                <th width="200">Name</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                        {{ $user->active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>

            {{-- ROLES --}}
            <tr>
                <th>Role(s)</th>
                <td>
                    @forelse ($user->roles as $role)
                        <span class="badge bg-primary">
                            {{ ucfirst($role->name) }}
                        </span>
                    @empty
                        <em>No roles assigned</em>
                    @endforelse
                </td>
            </tr>

            {{-- SCREEN + VENUE --}}
            <tr>
                <th>Assigned Screen(s)</th>
                <td>
                    @forelse ($user->screens as $screen)
                        <div class="mb-1">
                            <strong>{{ $screen->name }}</strong>
                            <span class="text-muted">
                                — {{ $screen->venue->name }}
                            </span>
                        </div>
                    @empty
                        <em>Not assigned to any screen</em>
                    @endforelse
                </td>
            </tr>
        </table>
    </div>
    @role('admin')
    <div class="card-footer text-end">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>
    @endrole
</div>
@endsection
