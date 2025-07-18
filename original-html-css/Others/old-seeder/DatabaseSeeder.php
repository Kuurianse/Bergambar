<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ArtistSeeder::class,
            ServiceSeeder::class,
            CommissionSeeder::class, // Tambahkan CommissionSeeder
        ]);
    }
}