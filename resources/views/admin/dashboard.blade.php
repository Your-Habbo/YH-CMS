@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        <div class="stats">
            <div class="stat-item">
                <h3>Users</h3>
                <p>{{ $userCount }}</p>
            </div>
            <!-- Add more stats as needed -->
        </div>
    </div>
@endsection
