<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Közös Képviselő',
            'email' => 'admin@tarsashaz.hu',
            'password' => bcrypt('password'),
            'ownership_ratio' => 0,
            'role_id' => 1, // admin
        ]);

        User::create([
            'name' => 'Nagy János',
            'email' => 'janos@tarsashaz.hu',
            'password' => bcrypt('password'),
            'ownership_ratio' => 35.5,
            'role_id' => 2, // owner
        ]);

        User::create([
            'name' => 'Kiss Éva',
            'email' => 'eva@tarsashaz.hu',
            'password' => bcrypt('password'),
            'ownership_ratio' => 25.0,
            'role_id' => 2, // owner
        ]);

        User::create([
            'name' => 'Számvizsgáló Béla',
            'email' => 'audit@tarsashaz.hu',
            'password' => bcrypt('password'),
            'ownership_ratio' => 0,
            'role_id' => 3, // auditor
        ]);
    }
}
