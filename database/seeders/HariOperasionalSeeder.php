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
        // Definisikan hari operasional berdasarkan lokasi
        $lokasiHari = [
            'Pusat' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            'Viktor' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            'Serang' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        ];

        // Ambil data lokasi yang bukan 'fleksible'
        $lokasis = Lokasi::whereNot('nama_lokasi', 'fleksible')->get();

        foreach ($lokasis as $lokasi) {
            // Tentukan hari operasional untuk lokasi tertentu
            $hariList = $lokasiHari[$lokasi->nama_lokasi] ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

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
