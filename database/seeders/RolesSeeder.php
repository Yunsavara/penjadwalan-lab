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
            'name' => 'user',
        ]);

        roles::create([
            'name' => 'superadmin',
        ]);

        roles::create([
            'name' => 'admin',
        ]);

        roles::create([
            'name' => 'laboran',
        ]);
    }
}
