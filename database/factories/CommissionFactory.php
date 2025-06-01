<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Mendapatkan ID user yang ada di database
        $users = User::pluck('id')->toArray();
        
        // Status commission yang mungkin
        $statuses = ['pending', 'accepted', 'completed'];
        
        return [
            'user_id' => $this->faker->randomElement($users),
            'title' => $this->faker->bs(), // Added title using a business slogan
            'status' => $this->faker->randomElement($statuses),
            'total_price' => $this->faker->randomFloat(2, 20, 500), // Harga antara 20 dan 500
            'description' => $this->faker->paragraph(3), // Deskripsi 3 paragraf
            'image' => 'commissions/default-'.$this->faker->numberBetween(1, 5).'.jpg', // Asumsikan ada gambar default
            'loved_count' => $this->faker->numberBetween(0, 100),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
    
    /**
     * State untuk commission dengan status pending
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }
    
    /**
     * State untuk commission dengan status accepted
     */
    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'accepted',
            ];
        });
    }
    
    /**
     * State untuk commission dengan status completed
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }
    
    /**
     * State untuk commission dengan harga tinggi
     */
    public function expensive()
    {
        return $this->state(function (array $attributes) {
            return [
                'total_price' => $this->faker->randomFloat(2, 300, 1000),
            ];
        });
    }
}