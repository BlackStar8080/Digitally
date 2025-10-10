<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            [
                'sports_name' => 'Basketball',
                'sports_details' => 'A team sport played with a ball on a rectangular court with hoops at each end. Teams of five players score by shooting the ball through the opponent\'s hoop.',
            ],
            [
                'sports_name' => 'Volleyball',
                'sports_details' => 'A team sport played with a ball and a net. Two teams of six players each try to score points by grounding the ball on the opponent\'s side of the court.',
            ],
        ];

        foreach ($sports as $sport) {
            Sport::create($sport);
        }
    }
}