<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount',
        'payment_date',
        'due_date',
        'status',
        'payment_method',
        'transaction_id',
        'description',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the student associated with this payment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope to get pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
