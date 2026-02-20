<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resolution;

class ResolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resolution::create([
            'agenda_item_id' => 1,
            'text' => 'A közgyűlés elfogadja a 2026. évi költségvetést.',
            'requires_unanimous' => false,
        ]);

        Resolution::create([
            'agenda_item_id' => 2,
            'text' => 'A közgyűlés jóváhagyja a tetőfelújítást.',
            'requires_unanimous' => true,
        ]);

    }
}
