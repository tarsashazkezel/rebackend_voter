<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AgendaItem;

class AgendaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgendaItem::create([
            'meeting_id' => 1,
            'title' => 'Éves költségvetés elfogadása',
            'description' => 'A 2026-os év tervezett költségvetése',
        ]);

        AgendaItem::create([
            'meeting_id' => 1,
            'title' => 'Tetőfelújítás',
            'description' => 'Rendes gazdálkodást meghaladó kiadás',
        ]);
    }
}
