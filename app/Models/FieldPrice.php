<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldPrice extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = [
        'field_id',
        'price_per_hour', // Sesuaikan nama kolom dengan database
        'start_time',
        'end_time',
        // 'is_weekend', // Hapus jika tidak digunakan, ganti dengan price_per_hour
    ];

    // Kolom yang otomatis di-cast
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        // 'is_weekend' => 'boolean', // Hapus jika tidak digunakan
    ];

    // Relasi: FieldPrice milik satu Field
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    // --- Method Helper untuk mencari harga ---
    // Cari harga berdasarkan waktu booking
    public static function findPriceForDateTime($fieldId, $dateTime)
    {
        $date = new \DateTime($dateTime);
        $time = $date->format('H:i');

        // Cari harga yang sesuai dengan field_id dan waktu booking
        return self::where('field_id', $fieldId)
                  ->where('start_time', '<=', $time)
                  ->where('end_time', '>', $time)
                  ->first(); // Kembalikan model FieldPrice atau null
    }
    // -----------------------------------------
}