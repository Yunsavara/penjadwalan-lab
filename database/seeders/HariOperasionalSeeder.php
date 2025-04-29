<?php

namespace Database\Seeders;

use App\Models\HariOperasional;
use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HariOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $lokasis = Lokasi::whereNot('nama_lokasi', 'fleksible')->get();

        foreach ($lokasis as $lokasi) {
            foreach ($hariList as $hari) {
                HariOperasional::create([
                    'lokasi_id' => $lokasi->id,
                    'hari_operasional' => $hari,
                    'is_disabled' => false, // Default aktif semua
                ]);
            }
        }

    }
}
