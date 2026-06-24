<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun role berbeda agar bisa langsung dipakai.
        // Password: "password" (sesuai validasi AuthController & factory).
        $akun = [
            [
                'name' => 'Customer Demo',
                'no_hp' => '081234567890',
                'role' => 'customer',
            ],
            [
                'name' => 'Admin Demo',
                'no_hp' => '081234567891',
                'role' => 'admin',
            ],
            [
                'name' => 'Kasir Demo',
                'no_hp' => '081234567892',
                'role' => 'cashier',
            ],
        ];

        foreach ($akun as $a) {
            User::updateOrCreate(
                ['no_hp' => $a['no_hp']],
                [
                    'name' => $a['name'],
                    'role' => $a['role'],
                    'password' => bcrypt('password'),
                ]
            );
        }
    }
}
