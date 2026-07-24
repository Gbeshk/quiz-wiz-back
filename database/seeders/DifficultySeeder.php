<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Difficulty;

class DifficultySeeder extends Seeder
{
    public function run(): void
    {
        $difficulties = [
            ['name' => 'Starter',          'color' => '#026AA2'],
            ['name' => 'Beginner',         'color' => '#175CD3'],
            ['name' => 'Middle',           'color' => '#6941C6'],
            ['name' => 'High',             'color' => '#B54708'],
            ['name' => 'Very high',        'color' => '#C11574'],
            ['name' => 'Dangerously high', 'color' => '#C01048'],
        ];

        foreach ($difficulties as $difficulty) {
            Difficulty::firstOrCreate(
                ['name' => $difficulty['name']],
                ['color' => $difficulty['color']]
            );
        }
    }
}
