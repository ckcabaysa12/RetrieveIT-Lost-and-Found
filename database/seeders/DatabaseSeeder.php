<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        User::firstOrCreate(
            ['email' => 'admin@lostfound.test'],
            [
                'name' => 'System Admin',
                'phone' => '09000000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'verification_status' => 'verified',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@lostfound.test'],
            [
                'name' => 'Demo User',
                'phone' => '09171234567',
                'password' => Hash::make('password'),
                'role' => 'user',
                'verification_status' => 'verified',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user2@lostfound.test'],
            [
                'name' => 'Maria Santos',
                'phone' => '09189876543',
                'password' => Hash::make('password'),
                'role' => 'user',
                'verification_status' => 'verified',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
