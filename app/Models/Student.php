<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roll_number',
        'enrollment_number',
        'program',
        'semester',
        'contact_number',
        'parent_contact',
        'address',
    ];

    /**
     * Get the user associated with this student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room assigned to this student.
     */
    public function room()
    {
        return $this->belongsToMany(Room::class, 'room_allocations')
            ->withPivot('allocation_date', 'release_date')
            ->withTimestamps();
    }

    /**
     * Get payments for this student.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
