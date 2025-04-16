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
        // Makin Kecil makin tinggi prioritasnya

        roles::create([
            'nama_peran' => 'admin',
            'prioritas_peran' => 1
        ]);

        roles::create([
            'nama_peran' => 'laboran',
            'prioritas_peran' => 2
        ]);

        roles::create([
            'nama_peran' => 'lembaga',
            'prioritas_peran' => 3
        ]);

        roles::create([
            'nama_peran' => 'prodi',
            'prioritas_peran' => 4
        ]);

        roles::create([
            'nama_peran' => 'user',
            'prioritas_peran' => 5
        ]);
    }
}
