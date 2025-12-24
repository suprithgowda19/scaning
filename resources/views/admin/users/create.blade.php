@extends('layouts.master')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Name</label>
                    <input class="form-control" name="name" required value="{{ old('name') }}">
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input class="form-control" name="email" type="email" required value="{{ old('email') }}">
                </div>

                <div class="col-md-6">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password" required>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
                <button class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
