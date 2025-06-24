<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\User;
// use App\Models\CommissionLove; // Removed as we'll use relationships
use App\Models\Review;
use App\Models\Service; // Added
use Illuminate\Database\Seeder;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat 20 commission dengan status acak
        // Commission::factory()->count(20)->create();
        
        // Buat 5 commission dengan status pending
        // Commission::factory()->pending()->count(5)->create();
        
        // Buat 5 commission dengan status accepted
        // Commission::factory()->accepted()->count(5)->create();
        
        // Buat 5 commission dengan status completed
        // Commission::factory()->completed()->count(5)->create();
        
        // Buat 5 commission mahal
        // Commission::factory()->expensive()->count(5)->create();

        $this->createSpecificCommissions(); // Added call to new method
        
        // Tambahkan juga data untuk CommissionLove
        $this->createLoves();
        
        // Tambahkan reviews
        $this->createReviews();
    }
    
    /**
     * Buat data untuk CommissionLove
     */
    private function createLoves()
    {
        $commissions = Commission::all();
        $users = User::all();
        
        // Untuk setiap commission, tambahkan beberapa love
        foreach ($commissions as $commission) {
            // Pilih beberapa user acak (1-X user, where X is min of 5 or actual user count)
            $userCount = $users->count();
            $randomUsers = collect(); // Initialize as empty collection
            if ($userCount > 0) {
                $numberOfUsersToPick = rand(1, min(5, $userCount));
                if ($numberOfUsersToPick > 0) { // Ensure we are trying to pick at least one
                    $randomUsers = $users->random($numberOfUsersToPick);
                    // If random() returns a single item when $numberOfUsersToPick is 1, ensure it's a collection
                    if (!$randomUsers instanceof \Illuminate\Support\Collection) {
                        $randomUsers = collect([$randomUsers]);
                    }
                }
            }
            
            foreach ($randomUsers as $user) {
                // Attach the user to the commission's loves relationship
                if (!$commission->loves()->where('user_id', $user->id)->exists()) {
                    $commission->loves()->attach($user->id, ['created_at' => now(), 'updated_at' => now()]);
                }
            }
            
            // Update loved_count
            $commission->loved_count = $commission->loves()->count();
            $commission->save();
        }
    }
    
    /**
     * Buat data untuk Reviews
     */
    private function createReviews()
    {
        $completedCommissions = Commission::where('status', 'completed')->get();
        $users = User::all();
        
        // Untuk setiap completed commission, tambahkan review
        foreach ($completedCommissions as $commission) {
            $faker = \Faker\Factory::create();
            
            // Tambahkan 1-3 review
            $reviewCount = rand(1, 3);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                // Pilih user acak yang bukan pemilik commission
                $otherUsers = $users->where('id', '!=', $commission->user_id);
                if ($otherUsers->isEmpty()) {
                    continue; // Skip if no other users to review this commission
                }
                $user = $otherUsers->random();
                
                Review::create([
                    'commission_id' => $commission->id,
                    'user_id' => $user->id,
                    'review' => $faker->paragraph(),
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createSpecificCommissions()
    {
        $placeholderImage = 'assets/image.jpg'; // Path relative to public folder

        $alyartUser = User::where('email', 'alya.putri@example.com')->first();
        $budisoundUser = User::where('email', 'budi.s@example.com')->first();

        // Ensure users and their artist profiles exist before trying to create commissions for them
        $clientUsers = User::where('role', 'user')->get(); // Get some client users

        if ($alyartUser && $alyartUser->artist) {
            $alyasArtistId = $alyartUser->artist->id;
            $clientForAlya = $clientUsers->isNotEmpty() ? $clientUsers->random()->id : $alyartUser->id; // Fallback to self if no clients

            $alyasPortraitService = Service::where('artist_id', $alyasArtistId)
                                   ->where('title', 'Custom Anime Character Portrait')
                                   ->first();
            $alyasConceptService = Service::where('artist_id', $alyasArtistId)
                                   ->where('title', 'Original Character Concept Sheet')
                                   ->first();

            if ($alyasPortraitService) {
                Commission::firstOrCreate(
                    ['artist_id' => $alyasArtistId, 'service_id' => $alyasPortraitService->id, 'description' => 'Detailed bust-up of original character "Elara" with floral background.'],
                    ['user_id' => $clientForAlya, 'title' => 'OC Elara Bust-up', 'status' => 'completed', 'total_price' => $alyasPortraitService->price, 'image' => $placeholderImage]
                );
            }
            Commission::firstOrCreate(
                ['artist_id' => $alyasArtistId, 'description' => 'Fantasy Landscape - "The Crystal Caves of Eldoria"'],
                ['user_id' => $clientForAlya, 'title' => 'Crystal Caves Landscape', 'status' => 'pending', 'total_price' => 950000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $alyasArtistId, 'description' => 'Chibi version of Link from Zelda'],
                ['user_id' => $clientForAlya, 'title' => 'Chibi Link (Zelda)', 'status' => 'needs_revision', 'total_price' => 350000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $alyasArtistId, 'description' => 'Set of 3 custom Twitch emotes (Hype, Sad, Love)'],
                ['user_id' => $clientForAlya, 'title' => 'Twitch Emote Set (3)', 'status' => 'completed', 'total_price' => 450000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $alyasArtistId, 'description' => 'Illustrated banner for YouTube gaming channel "PixelPlay"'],
                ['user_id' => $clientForAlya, 'title' => 'PixelPlay YouTube Banner', 'status' => 'completed', 'total_price' => 600000, 'image' => $placeholderImage]
            );
            if ($alyasConceptService) {
                Commission::firstOrCreate(
                    ['artist_id' => $alyasArtistId, 'service_id' => $alyasConceptService->id, 'description' => 'Concept art for a mythical Griffin'],
                    ['user_id' => $clientForAlya, 'title' => 'Mythical Griffin Concept', 'status' => 'completed', 'total_price' => $alyasConceptService->price, 'image' => $placeholderImage]
                );
            }
        }

        if ($budisoundUser && $budisoundUser->artist) {
            $budisArtistId = $budisoundUser->artist->id;
            $clientForBudi = $clientUsers->isNotEmpty() ? $clientUsers->random()->id : $budisoundUser->id; // Fallback to self if no clients

            $budisLofiService = Service::where('artist_id', $budisArtistId)
                                   ->where('title', 'Lo-fi Chill Beat (60 seconds)')
                                   ->first();
            $budisOrchestralService = Service::where('artist_id', $budisArtistId)
                                   ->where('title', 'Epic Orchestral Loop (30 seconds)')
                                   ->first();

            if ($budisLofiService) {
                Commission::firstOrCreate(
                    ['artist_id' => $budisArtistId, 'service_id' => $budisLofiService->id, 'description' => 'Custom Lo-fi track for "Relaxing Rain" animation video.'],
                    ['user_id' => $clientForBudi, 'title' => 'Lo-fi for Relaxing Rain', 'status' => 'completed', 'total_price' => $budisLofiService->price, 'image' => $placeholderImage]
                );
            }
            Commission::firstOrCreate(
                ['artist_id' => $budisArtistId, 'description' => 'Sound design for a short indie game trailer "Galaxy Runner" (30s)'],
                ['user_id' => $clientForBudi, 'title' => 'Galaxy Runner Trailer SFX', 'status' => 'pending', 'total_price' => 1500000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $budisArtistId, 'description' => 'Intro/Outro jingle for "Tech Review Today" podcast (15 seconds)'],
                ['user_id' => $clientForBudi, 'title' => 'Tech Review Podcast Jingle', 'status' => 'accepted', 'total_price' => 400000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $budisArtistId, 'description' => 'Pack of 10 unique UI sound effects for "TaskMaster" mobile app'],
                ['user_id' => $clientForBudi, 'title' => 'TaskMaster App UI SFX (10)', 'status' => 'completed', 'total_price' => 700000, 'image' => $placeholderImage]
            );
            Commission::firstOrCreate(
                ['artist_id' => $budisArtistId, 'description' => '5-minute ambient background track for "Mindful Moments" meditation app'],
                ['user_id' => $clientForBudi, 'title' => 'Mindful Moments Ambient Track', 'status' => 'pending', 'total_price' => 900000, 'image' => $placeholderImage]
            );
            if ($budisOrchestralService) {
                 Commission::firstOrCreate( // Price override for a longer version
                    ['artist_id' => $budisArtistId, 'service_id' => $budisOrchestralService->id, 'description' => 'Epic battle theme loop (extended 60 seconds version)'],
                    ['user_id' => $clientForBudi, 'title' => 'Epic Battle Loop (Extended)', 'status' => 'accepted', 'total_price' => 1000000, 'image' => $placeholderImage]
                );
            }
        }
    }
}