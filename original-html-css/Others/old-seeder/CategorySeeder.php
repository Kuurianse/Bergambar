<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Illustration', 'description' => 'Digital and traditional illustration services'],
            ['name' => 'Music', 'description' => 'Music composition and arrangement'],
            ['name' => 'Rigging', 'description' => 'Character rigging for animation'],
            ['name' => 'Character Design', 'description' => 'Original character design and concept art'],
            ['name' => 'Animation', 'description' => '2D and 3D animation services']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}