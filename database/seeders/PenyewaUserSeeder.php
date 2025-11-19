<?php

namespace Database\Seeders;

use App\Models\User; // Pastikan model User diimpor
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash untuk enkripsi password

class PenyewaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat satu akun penyewa
        // User::create([
        //     'name' => 'Penyewa User',        // Nama pengguna
        //     'email' => 'test@user.com',      // Email akun penyewa
        //     'password' => Hash::make('password'), // Enkripsi password
        //     'role' => 'penyewa',             // Set role menjadi 'penyewa'
        // ]);

        // Anda bisa menambahkan lebih banyak akun di sini jika diperlukan
        User::create([
            'name' => 'Penyewa Lain',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role' => 'penyewa',
        ]);
    }
}