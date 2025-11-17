<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'payment_date',
        'status',
    ];

    // Kolom yang otomatis di-cast
    protected $casts = [
        'amount' => 'integer', // Atau decimal
        'payment_date' => 'datetime',
        'status' => 'string',
    ];

    // Relasi: Payment milik satu Booking
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}