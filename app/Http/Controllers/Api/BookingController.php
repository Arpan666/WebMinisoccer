<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\FieldPrice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // ... (method indexUserBookings tetap sama)

    /**
     * Store a newly created booking in storage.
     * Endpoint: POST /api/bookings
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            // Hapus 'is_active,1' dari aturan exists
            'field_id' => 'required|exists:fields,id', // Cukup cek apakah ID lapangan ada
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1|max:12',
        ]);

        $fieldId = $validatedData['field_id'];
        $bookingDate = $validatedData['date'];
        $startTimeInput = $validatedData['start_time'];
        $duration = $validatedData['duration'];

        // 1. Cek apakah lapangan *secara konsep* tersedia di waktu tersebut (berdasarkan status)
        $field = Field::findOrFail($fieldId);
        if ($field->status !== 'tersedia') { // Gunakan status saja sekarang
             return response()->json(['message' => 'Lapangan saat ini tidak tersedia.'], 422);
        }

        // 2. Cek apakah lapangan *sudah dipesan* di waktu yang tumpang tindih
        // (kode untuk cek tumpang tindih tetap sama)
        $bookingStartDateTime = $bookingDate . ' ' . $startTimeInput;
        $bookingEndDateTime = date('Y-m-d H:i:s', strtotime($bookingStartDateTime . ' + ' . $duration . ' hours'));

        $existingBooking = Booking::where('field_id', $fieldId)
            ->where('date', $bookingDate)
            ->where(function ($query) use ($startTimeInput, $bookingEndDateTime) {
                $query->where('start_time', '<', date('H:i:s', strtotime($bookingEndDateTime)))
                      ->where(date('H:i:s', strtotime($bookingStartDateTime)), '<', \DB::raw('TIME_ADD(bookings.start_time, INTERVAL duration HOUR)'));
            })
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'Lapangan sudah dipesan pada waktu tersebut.'], 422);
        }

        // 3. Cari harga berlaku untuk waktu booking ini (kode tetap sama)
        $fieldPrice = FieldPrice::where('field_id', $fieldId)
            ->where('start_time', '<=', date('H:i:s', strtotime($bookingStartDateTime)))
            ->where('end_time', '>', date('H:i:s', strtotime($bookingStartDateTime)))
            ->first();

        if (!$fieldPrice) {
            return response()->json(['message' => 'Harga untuk waktu tersebut tidak ditemukan.'], 422);
        }

        $pricePerHour = $fieldPrice->price_per_hour;
        $totalPrice = $pricePerHour * $duration;

        // 4. Hitung end_time (kode tetap sama)
        $endTime = date('H:i:s', strtotime($startTimeInput . ' + ' . $duration . ' hours'));

        // 5. Buat booking baru (kode tetap sama)
        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->field_id = $fieldId;
        $booking->date = $bookingDate;
        $booking->start_time = $startTimeInput;
        $booking->duration = $duration;
        $booking->end_time = $endTime;
        $booking->total_price = $totalPrice;
        $booking->status = 'pending'; // Pastikan status default di-set
        $booking->save();

        $booking->load('field', 'user', 'payment');

        return response()->json($booking, 201);
    }

    // ... (method show tetap sama)
}