<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allocation extends Model
{
    protected $fillable = [
        'student_id', 'room_id', 'allocation_date', 
        'end_date', 'status', 'notes'
    ];
    
    protected $casts = [
        'allocation_date' => 'date',
        'end_date' => 'date'
    ];
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'end_date' => now()
        ]);
    }
    
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}