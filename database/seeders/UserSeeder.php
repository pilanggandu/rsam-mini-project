<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name'              => 'Dr. Andi Dokter',
                'password'          => Hash::make('password'),
                'role'              => 'doctor',
                'email_verified_at' => now(),
            ]
        );

        // Apoteker
        User::updateOrCreate(
            ['email' => 'pharmacist@example.com'],
            [
                'name'              => 'Apoteker Siti',
                'password'          => Hash::make('password'),
                'role'              => 'pharmacist',
                'email_verified_at' => now(),
            ]
        );
    }
}
