<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meeting;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Meeting::create([
            'title' => 'Éves rendes közgyűlés',
            'meeting_date' => now()->addDays(7),
            'location' => 'Társasház közösségi terem',
            'created_by' => 1, // admin
        ]);
    }
}
