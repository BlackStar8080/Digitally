<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sport::create([
            'sports_name' => 'Basketball',
            'sports_details' => 'A team sport where two teams compete to score by shooting a ball through the opposing team\'s hoop.'
        ]);

        Sport::create([
            'sports_name' => 'Volleyball',
            'sports_details' => 'A team sport where two teams are separated by a net and score points by hitting the ball over the net onto the opposing team\'s court.'
        ]);
    }
}