<?php

namespace Database\Seeders;

use App\Models\roles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        roles::create([
            'name' => 'admin',
            'priority' => 5
        ]);

        roles::create([
            'name' => 'laboran',
            'priority' => 4

        ]);

        roles::create([
            'name' => 'lembaga',
            'priority' => 1
        ]);

        roles::create([
            'name' => 'prodi',
            'priority' => 2
        ]);

        roles::create([
            'name' => 'user',
            'priority' => 3
        ]);
    }
}
