<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users
            $table->foreignId('field_id')->constrained()->onDelete('cascade'); // Relasi ke fields
            $table->date('date'); // Tanggal bermain
            $table->time('start_time'); // Jam mulai input user
            $table->integer('duration'); // Durasi dalam jam
            $table->time('end_time'); // Jam selesai (akan dihitung otomatis)
            $table->integer('total_price'); // Harga total (akan dihitung otomatis)
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending'); // Sesuai desain

            // Tidak menambahkan foreign key payment_id di sini untuk menghindari error
            // Kita akan tambahkan nanti setelah payments dibuat
            $table->unsignedBigInteger('payment_id')->nullable(); // Kolom biasa dulu

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};