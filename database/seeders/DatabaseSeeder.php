<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun bootstrap untuk pengelolaan sistem. Ganti ADMIN_PASSWORD di .env.
        User::updateOrCreate(
            ['username' => env('ADMIN_USERNAME', 'admin')],
            [
                'name' => 'Administrator Sistem Jadwal',
                'email' => env('ADMIN_EMAIL', 'admin@localhost'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin-change-me')),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Hanya muat data akademik resmi, tanpa data contoh dashboard.
        $this->call([
            RealScheduleSeeder::class,
            Curriculum2022Seeder::class,
        ]);
    }
}
