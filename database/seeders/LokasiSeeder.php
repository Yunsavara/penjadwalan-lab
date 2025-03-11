<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lokasi::create([
            'name' => 'Pusat',
        ]);

        Lokasi::create([
            'name' => 'Viktor',
        ]);

        Lokasi::create([
            'name' => 'Serang',
        ]);
    }
}
