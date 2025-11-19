<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected static ?string $title = 'Tambah Booking Baru';

    // Gunakan hook ini untuk memanipulasi data sebelum disimpan
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil data yang diperlukan untuk perhitungan
        $fieldId = $data['field_id'] ?? null;
        $date = $data['date'] ?? null;
        $startTime = $data['start_time'] ?? null;
        $duration = $data['duration'] ?? null;

        // Jika semua data yang diperlukan ada, lakukan perhitungan
        if ($fieldId && $date && $startTime && $duration) {
            // Buat instance model sementara untuk menghitung
            $booking = new \App\Models\Booking();
            $booking->field_id = $fieldId;
            $booking->date = $date;
            $booking->start_time = $startTime;
            $booking->duration = $duration;

            // Hitung end_time dan total_price menggunakan method di model
            $data['end_time'] = $booking->calculateEndTime();
            $data['total_price'] = $booking->calculateTotalPrice();
        }

        return $data;
    }
}