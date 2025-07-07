<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    public function run()
    {
        $artistData = [
            [
                'email' => 'alya.putri@example.com',
                'portfolio_link' => 'https://alyart.myportfolio.com',
                'is_verified' => true, // Example: Alya is verified
                'rating' => 4.75,      // Example rating for Alya
            ],
            [
                'email' => 'budi.s@example.com',
                'portfolio_link' => 'https://budisound.bandcamp.com',
                'is_verified' => false, // Example: Budi is not yet verified
                // 'rating' will default to null if not provided
            ],
        ];

        foreach ($artistData as $data) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                // Check if an artist profile already exists for this user to avoid duplicates
                if (!Artist::where('user_id', $user->id)->exists()) {
                    Artist::create([
                        'user_id' => $user->id,
                        'portfolio_link' => $data['portfolio_link'] ?? null,
                        'is_verified' => $data['is_verified'] ?? false,
                        'rating' => $data['rating'] ?? null,
                    ]);
                }
            }
        }
    }
}