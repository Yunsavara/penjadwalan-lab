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
            'nama_lokasi' => 'fleksible',
            'deskripsi_lokasi' => 'untuk pengguna selain peran laboran'
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Pusat',
            'deskripsi_lokasi' => 'untuk pengguna peran laboran'
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Viktor',
            'deskripsi_lokasi' => 'untuk pengguna peran laboran'
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Serang',
            'deskripsi_lokasi' => 'untuk pengguna peran laboran'
        ]);
    }
}
