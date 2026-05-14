@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-cog fa-4x text-primary mb-3"></i>
        <h4>System Settings</h4>
        <p class="text-muted">Settings panel coming soon...</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
@endsection