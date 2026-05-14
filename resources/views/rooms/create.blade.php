@extends('layouts.app')

@section('title', 'Add Room')
@section('page-title', 'Add New Room')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('rooms.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Room Number *</label>
                    <input type="text" name="room_number" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Capacity *</label>
                    <input type="number" name="capacity" class="form-control" required min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Floor</label>
                    <input type="text" name="floor" class="form-control" placeholder="e.g., Ground, 1, 2">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Price per Month (₦)</label>
                    <input type="number" name="price_per_month" class="form-control" step="0.01">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Room</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection