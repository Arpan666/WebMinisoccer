<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = [
        'user_id',
        'field_id',
        'payment_id',
        'date',
        'start_time',
        'duration',
        'end_time',
        'total_price',
        'status',
    ];

    // Kolom yang otomatis di-cast
    protected $casts = [
        'date' => 'date', // Cast ke date
        'start_time' => 'datetime:H:i', // Cast ke time
        'end_time' => 'datetime:H:i',   // Cast ke time
        'duration' => 'integer',
        'total_price' => 'integer', // Atau decimal jika harga bisa koma
        'status' => 'string',
    ];

    // Relasi: Booking milik satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Booking untuk satu Field
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    // Relasi: Booking memiliki satu Payment
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}