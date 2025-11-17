<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Relasi ke bookings
            $table->integer('amount'); // Jumlah pembayaran
            $table->enum('method', ['cash', 'transfer', 'qris']); // Metode pembayaran
            $table->dateTime('payment_date'); // Tanggal pembayaran
            $table->enum('status', ['success', 'failed'])->default('success'); // Status pembayaran
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};