<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Artist;
use App\Models\User; // Added
use App\Models\Category; // Added

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $alyartUser = User::where('email', 'alya.putri@example.com')->first();
        $budisoundUser = User::where('email', 'budi.s@example.com')->first();

        $illustrationCategory = Category::where('name', 'Illustration')->first();
        $charDesignCategory = Category::where('name', 'Character Design')->first();
        $musicCategory = Category::where('name', 'Music')->first();

        // Seed services only if the categories and artist profiles exist
        if ($alyartUser && $alyartUser->artist) {
            if ($illustrationCategory) {
                Service::firstOrCreate(
                    [
                        'artist_id' => $alyartUser->artist->id,
                        'title' => 'Custom Anime Character Portrait',
                    ],
                    [
                        'category_id' => $illustrationCategory->id,
                        'description' => 'Full-color, high-detail anime style portrait of your original character or fan art. Bust-up or half-body.',
                        'price' => 750000,
                        'service_type' => 'illustration',
                        'availability_status' => true,
                    ]
                );
            }
            if ($charDesignCategory) {
                 Service::firstOrCreate(
                    [
                        'artist_id' => $alyartUser->artist->id,
                        'title' => 'Original Character Concept Sheet',
                    ],
                    [
                        'category_id' => $charDesignCategory->id,
                        'description' => 'Detailed concept sheet including front, back, side views and expression sketches for your OC.',
                        'price' => 1200000,
                        'service_type' => 'illustration',
                        'availability_status' => true,
                    ]
                );
            }
        }

        if ($budisoundUser && $budisoundUser->artist) {
            if ($musicCategory) {
                Service::firstOrCreate(
                    [
                        'artist_id' => $budisoundUser->artist->id,
                        'title' => 'Lo-fi Chill Beat (60 seconds)',
                    ],
                    [
                        'category_id' => $musicCategory->id,
                        'description' => 'Custom 60-second lo-fi chill beat, perfect for background music or streaming.',
                        'price' => 500000,
                        'service_type' => 'music',
                        'availability_status' => true,
                    ]
                );
                Service::firstOrCreate(
                    [
                        'artist_id' => $budisoundUser->artist->id,
                        'title' => 'Epic Orchestral Loop (30 seconds)',
                    ],
                    [
                        'category_id' => $musicCategory->id,
                        'description' => 'A short, loopable orchestral piece for game trailers or intense scenes.',
                        'price' => 800000,
                        'service_type' => 'music',
                        'availability_status' => false, // Example of an unavailable service
                    ]
                );
            }
        }
    }
}