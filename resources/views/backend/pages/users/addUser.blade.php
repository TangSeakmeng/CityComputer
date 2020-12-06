@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/users/addUser.css') }}">
@endsection

@section('content')
    <div id="addUserContainer">
        <h1 class="display-4">Add User</h1>

        @if ($notification = Session::get('success'))
            <div class="alert alert-success alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        @if ($notification = Session::get('error'))
            <div class="alert alert-danger alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/users">
            @csrf

            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="name">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="email" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="name">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required autocomplete="off">
            </div>

            <button class="btn btn-primary" type="submit">Add User</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/users">
                <button class="btn btn-warning" type="button">Back to Users Dashboard</button>
            </a>
        </form>
    </div>
@endsection
