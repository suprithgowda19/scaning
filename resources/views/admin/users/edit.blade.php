@extends('layouts.master')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Name</label>
                    <input class="form-control" name="name" required value="{{ old('name', $user->name) }}">
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input class="form-control" name="email" type="email"
                           required value="{{ old('email', $user->email) }}">
                </div>

                <div class="col-md-6">
                    <label>New Password (optional)</label>
                    <input class="form-control" name="password" type="password">
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
                <button class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection
