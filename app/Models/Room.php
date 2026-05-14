<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'capacity', 'status', 'floor', 'price_per_month', 'description'
    ];
    
    protected $casts = [
        'capacity' => 'integer',
        'price_per_month' => 'decimal:2'
    ];
    
    // Relationship with allocations
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }
    
    // Get active allocations count using relationship
    public function activeAllocations()
    {
        return $this->allocations()->where('status', 'active');
    }
    
    // Get occupancy count
    public function getOccupancyCountAttribute()
    {
        return $this->activeAllocations()->count();
    }
    
    // Get available beds
    public function getAvailableBedsAttribute()
    {
        return $this->capacity - $this->getOccupancyCountAttribute();
    }
    
    // Check if room is full
    public function isFull()
    {
        return $this->getAvailableBedsAttribute() <= 0;
    }
}