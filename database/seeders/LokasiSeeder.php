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
            'nama_lokasi' => 'fleksible'
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Pusat',
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Viktor',
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Serang',
        ]);
    }
}
