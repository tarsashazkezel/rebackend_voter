<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vote;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Vote::create([
            'resolution_id' => 1,
            'user_id' => 2,
            'vote' => 'yes',
        ]);

        Vote::create([
            'resolution_id' => 1,
            'user_id' => 3,
            'vote' => 'yes',
        ]);

        Vote::create([
            'resolution_id' => 2,
            'user_id' => 2,
            'vote' => 'yes',
        ]);

        Vote::create([
            'resolution_id' => 2,
            'user_id' => 3,
            'vote' => 'no',
        ]);
    }
}
