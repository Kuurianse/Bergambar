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
        $alyartUser = User::where('email', 'alya.putri@example.com')->first();
        $budisoundUser = User::where('email', 'budi.s@example.com')->first();

        // Ensure users and their artist profiles exist before trying to create commissions for them
        if ($alyartUser && $alyartUser->artist) {
            $alyasPortraitService = Service::where('artist_id', $alyartUser->artist->id)
                                   ->where('title', 'Custom Anime Character Portrait')
                                   ->first();
            $alyasConceptService = Service::where('artist_id', $alyartUser->artist->id)
                                   ->where('title', 'Original Character Concept Sheet')
                                   ->first();

            if ($alyasPortraitService) {
                Commission::firstOrCreate(
                    ['user_id' => $alyartUser->id, 'service_id' => $alyasPortraitService->id, 'description' => 'Detailed bust-up of original character "Elara" with floral background.'],
                    ['title' => 'OC Elara Bust-up', 'status' => 'completed', 'total_price' => $alyasPortraitService->price, 'image' => 'seed_images/elara_commission.jpg']
                );
            }
            Commission::firstOrCreate(
                ['user_id' => $alyartUser->id, 'description' => 'Fantasy Landscape - "The Crystal Caves of Eldoria"'],
                ['title' => 'Crystal Caves Landscape', 'status' => 'pending', 'total_price' => 950000, 'image' => 'seed_images/crystal_caves.jpg']
            );
            Commission::firstOrCreate(
                ['user_id' => $alyartUser->id, 'description' => 'Chibi version of Link from Zelda'],
                ['title' => 'Chibi Link (Zelda)', 'status' => 'accepted', 'total_price' => 350000, 'image' => 'seed_images/chibi_link.jpg']
            );
            Commission::firstOrCreate(
                ['user_id' => $alyartUser->id, 'description' => 'Set of 3 custom Twitch emotes (Hype, Sad, Love)'],
                ['title' => 'Twitch Emote Set (3)', 'status' => 'completed', 'total_price' => 450000, 'image' => 'seed_images/emote_set.png']
            );
            Commission::firstOrCreate(
                ['user_id' => $alyartUser->id, 'description' => 'Illustrated banner for YouTube gaming channel "PixelPlay"'],
                ['title' => 'PixelPlay YouTube Banner', 'status' => 'pending', 'total_price' => 600000, 'image' => 'seed_images/pixelplay_banner.jpg']
            );
            if ($alyasConceptService) {
                Commission::firstOrCreate(
                    ['user_id' => $alyartUser->id, 'service_id' => $alyasConceptService->id, 'description' => 'Concept art for a mythical Griffin'],
                    ['title' => 'Mythical Griffin Concept', 'status' => 'accepted', 'total_price' => $alyasConceptService->price, 'image' => 'seed_images/griffin_concept.jpg']
                );
            }
        }

        if ($budisoundUser && $budisoundUser->artist) {
            $budisLofiService = Service::where('artist_id', $budisoundUser->artist->id)
                                   ->where('title', 'Lo-fi Chill Beat (60 seconds)')
                                   ->first();
            $budisOrchestralService = Service::where('artist_id', $budisoundUser->artist->id)
                                   ->where('title', 'Epic Orchestral Loop (30 seconds)')
                                   ->first();

            if ($budisLofiService) {
                Commission::firstOrCreate(
                    ['user_id' => $budisoundUser->id, 'service_id' => $budisLofiService->id, 'description' => 'Custom Lo-fi track for "Relaxing Rain" animation video.'],
                    ['title' => 'Lo-fi for Relaxing Rain', 'status' => 'completed', 'total_price' => $budisLofiService->price]
                );
            }
            Commission::firstOrCreate(
                ['user_id' => $budisoundUser->id, 'description' => 'Sound design for a short indie game trailer "Galaxy Runner" (30s)'],
                ['title' => 'Galaxy Runner Trailer SFX', 'status' => 'pending', 'total_price' => 1500000]
            );
            Commission::firstOrCreate(
                ['user_id' => $budisoundUser->id, 'description' => 'Intro/Outro jingle for "Tech Review Today" podcast (15 seconds)'],
                ['title' => 'Tech Review Podcast Jingle', 'status' => 'accepted', 'total_price' => 400000]
            );
            Commission::firstOrCreate(
                ['user_id' => $budisoundUser->id, 'description' => 'Pack of 10 unique UI sound effects for "TaskMaster" mobile app'],
                ['title' => 'TaskMaster App UI SFX (10)', 'status' => 'completed', 'total_price' => 700000]
            );
            Commission::firstOrCreate(
                ['user_id' => $budisoundUser->id, 'description' => '5-minute ambient background track for "Mindful Moments" meditation app'],
                ['title' => 'Mindful Moments Ambient Track', 'status' => 'pending', 'total_price' => 900000]
            );
            if ($budisOrchestralService) {
                 Commission::firstOrCreate( // Price override for a longer version
                    ['user_id' => $budisoundUser->id, 'service_id' => $budisOrchestralService->id, 'description' => 'Epic battle theme loop (extended 60 seconds version)'],
                    ['title' => 'Epic Battle Loop (Extended)', 'status' => 'accepted', 'total_price' => 1000000]
                );
            }
        }
    }
}