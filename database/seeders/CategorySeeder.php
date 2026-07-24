<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Geography',
            'Music',
            'Movies',
            'Television',
            'Just for fun',
            'Personality',
            'Sports',
            'History',
            'Language',
            'Literature',
            'Philosophy',
            'Math',
            'Toys',
            'Entertainment',
            'Olympics',
            'Greek philosophy',
            'Animations',
            'Psychology',
            'ART',
            'Physics',
            'Human Anatomy',
            'Geometry',
            'Dance',
            'Gadgets',
            'Internet',
            'Religion',
            'Gaming',
            'Travel',
            'Trivia',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
