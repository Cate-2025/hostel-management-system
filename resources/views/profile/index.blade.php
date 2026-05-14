@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
        <h4>{{ Auth::user()->name }}</h4>
        <p class="text-muted">{{ Auth::user()->email }}</p>
        <hr>
        <p class="text-muted">Profile management coming soon...</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
@endsection