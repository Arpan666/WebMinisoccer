<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = [
        'name',
        'description',
        'status', // Gunakan hanya 'status' sekarang
    ];

    // Relasi: Field memiliki banyak Booking
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Relasi: Field memiliki banyak FieldPrice
    public function prices(): HasMany
    {
        return $this->hasMany(FieldPrice::class);
    }
}