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
        'total_price' => 'integer',
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

    // --- Method Helper untuk menghitung otomatis ---
    /**
     * Hitung Jam Selesai berdasarkan Jam Mulai dan Durasi.
     */
    public function calculateEndTime()
    {
        if (!$this->start_time || !$this->duration) {
            return null;
        }

        // Ambil hanya bagian tanggal dari $this->date
        $datePart = $this->date instanceof \Carbon\Carbon ? $this->date->format('Y-m-d') : $this->date;

        // Ambil hanya bagian waktu dari $this->start_time jika dalam format datetime
        // Kita asumsikan $this->start_time adalah string waktu (H:i) atau datetime (Y-m-d H:i)
        $timePart = $this->start_time;
        if (strtotime($this->start_time) !== false && strlen($this->start_time) > 5) { // Jika formatnya panjang, mungkin datetime
            $parsedTime = \Carbon\Carbon::parse($this->start_time);
            $timePart = $parsedTime->format('H:i'); // Ambil hanya jam:menit
        }

        // Gabungkan tanggal dan hanya waktu
        $startDateTime = \Carbon\Carbon::parse($datePart . ' ' . $timePart);

        // Tambahkan durasi
        $endDateTime = $startDateTime->addHours($this->duration);

        // Kembalikan hanya jam dan menit
        return $endDateTime->format('H:i');
    }

    /**
     * Hitung Total Harga berdasarkan FieldPrice yang sesuai.
     */
    public function calculateTotalPrice()
    {
        if (!$this->field_id || !$this->start_time || !$this->duration) {
            return 0;
        }

        // Ambil hanya bagian tanggal dari $this->date
        $datePart = $this->date instanceof \Carbon\Carbon ? $this->date->format('Y-m-d') : $this->date;

        // Ambil hanya bagian waktu dari $this->start_time untuk pencarian harga
        $timePart = $this->start_time;
        if (strtotime($this->start_time) !== false && strlen($this->start_time) > 5) { // Jika formatnya panjang, mungkin datetime
            $parsedTime = \Carbon\Carbon::parse($this->start_time);
            $timePart = $parsedTime->format('H:i'); // Ambil hanya jam:menit
        }

        // Cari harga yang sesuai berdasarkan waktu booking (hanya waktu)
        $fieldPrice = \App\Models\FieldPrice::where('field_id', $this->field_id)
            ->where('start_time', '<=', $timePart)
            ->where('end_time', '>', $timePart)
            ->first();

        if (!$fieldPrice) {
            return 0; // Atau kembalikan error jika harga tidak ditemukan
        }

        return $fieldPrice->price_per_hour * $this->duration;
    }
    // ---------------------------------------------
}