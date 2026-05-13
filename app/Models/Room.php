<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'capacity',
        'available_beds',
        'floor',
        'type',
        'status',
        'maintenance_notes',
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string',
    ];

    /**
     * Get students allocated to this room.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'room_allocations')
            ->withPivot('allocation_date', 'release_date')
            ->withTimestamps();
    }

    /**
     * Scope to get available rooms.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->where('available_beds', '>', 0);
    }
}
