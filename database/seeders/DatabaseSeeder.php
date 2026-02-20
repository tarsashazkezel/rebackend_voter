<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Meeting;
use App\Models\AgendaItem;
use App\Models\Resolution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Szerepkörök létrehozása
        $adminRole = Role::create(['id' => 1, 'name' => 'admin']);
        $ownerRole = Role::create(['id' => 2, 'name' => 'owner']);

        // 2. Admin (Közös képviselő) létrehozása
        User::create([
            'name' => 'Kovács Aladár (Közös Képviselő)',
            'email' => 'admin@haz.hu',
            'password' => Hash::make('password123'),
            'ownership_ratio' => 0,
            'role_id' => $adminRole->id,
        ]);

        // 3. Tulajdonosok létrehozása (Összesen 100.00 tulajdoni hányad)
        $owners = [
            ['name' => 'Minta János', 'email' => 'janos@haz.hu', 'ratio' => 25.50],
            ['name' => 'Nagy Erzsébet', 'email' => 'erzsi@haz.hu', 'ratio' => 15.00],
            ['name' => 'Kiss István', 'email' => 'pista@haz.hu', 'ratio' => 10.25],
            ['name' => 'Tóth Ottó', 'email' => 'otto@haz.hu', 'ratio' => 49.25],
        ];

        foreach ($owners as $owner) {
            User::create([
                'name' => $owner['name'],
                'email' => $owner['email'],
                'password' => Hash::make('password123'),
                'ownership_ratio' => $owner['ratio'],
                'role_id' => $ownerRole->id,
            ]);
        }

        // 4. Közgyűlés létrehozása
        $meeting = Meeting::create([
            'title' => '2025. Évi Rendes Beszámoló Közgyűlés',
            'meeting_date' => now()->addDays(2),
            'location' => 'Társasház belső udvar / Online',
            'created_by' => 1,
        ]);

        // 5. Napirendi pontok és Határozati javaslatok
        $agendas = [
            [
                'title' => 'Jegyzőkönyvvezető és hitelesítők megválasztása',
                'description' => 'A közgyűlés tisztségviselőinek kijelölése a jegyzőkönyv hitelesítéséhez.',
                'res' => 'A közgyűlés elfogadja Minta Jánost jegyzőkönyvvezetőnek.'
            ],
            [
                'title' => '2024. évi pénzügyi beszámoló elfogadása',
                'description' => 'A tavalyi év gazdálkodásának részletes ismertetése és jóváhagyása.',
                'res' => 'A közgyűlés a 2024-es beszámolót 12.450.000 Ft maradvánnyal elfogadja.'
            ],
            [
                'title' => 'Tetőfelújítás megkezdése',
                'description' => 'A beázások megszüntetése érdekében szükséges generálkivitelezés megszavazása.',
                'res' => 'A közgyűlés felhatalmazza a közös képviselőt a tetőfelújítási szerződés aláírására.'
            ],
        ];

        foreach ($agendas as $a) {
            $item = AgendaItem::create([
                'title' => $a['title'],
                'description' => $a['description'],
                'meeting_id' => $meeting->id,
            ]);

            Resolution::create([
                'text' => $a['res'],
                'agenda_item_id' => $item->id,
                'requires_unanimous' => false
            ]);
        }
    }
}