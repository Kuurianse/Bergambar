<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin
        User::create([
            'name' => 'Site Administrator',
            'email' => 'admin@example.com', // Kept generic for example
            'username' => 'SuperAdmin',
            'bio' => 'Overseeing the platform.',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(), // Mark admin as verified/active
        ]);

        // Buat beberapa artist (User accounts that will have Artist profiles)
        User::create([
            'name' => 'Alya Putri',
            'email' => 'alya.putri@example.com',
            'username' => 'alyart',
            'bio' => 'Digital illustrator focusing on fantasy characters and vibrant scenes.',
            'password' => Hash::make('password123'),
            'role' => 'user', // Role is 'user', artist-specific data is in Artist model
            'email_verified_at' => now(), // Mark as verified/active
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.s@example.com',
            'username' => 'budisound',
            'bio' => 'Composer creating epic scores for indie games.',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(), // Mark as verified/active
        ]);

        // Buat user biasa
        User::create([
            'name' => 'Citra Dewi',
            'email' => 'citra.d@example.com',
            'username' => 'artlover88',
            'bio' => 'Collector of unique digital art.',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(), // Mark as verified/active
        ]);
    }
}