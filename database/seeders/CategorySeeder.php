<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'قطع غيار التكييفات', 'slug' => 'air-conditioner-parts'],
            ['name' => 'قطع غيار الثلاجات', 'slug' => 'refrigerator-parts'],
            ['name' => 'قطع غيار الغسالات', 'slug' => 'washing-machine-parts'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => true,
            ]);
        }
    }
}
