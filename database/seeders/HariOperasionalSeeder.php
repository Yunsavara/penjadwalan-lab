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
        // Mapping hari dalam angka sesuai getDay()
        // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
        $lokasiHari = [
            'Pusat' => [1, 2, 3, 4, 5],             // Senin–Jumat
            'Viktor' => [1, 2, 3, 4, 5, 6],         // Senin–Sabtu
            'Serang' => [0, 1, 2, 3, 4, 5, 6],      // Semua hari
        ];
    
        // Ambil data lokasi (kecuali fleksible)
        $lokasis = Lokasi::whereNot('nama_lokasi', 'fleksible')->get();
    
        foreach ($lokasis as $lokasi) {
            $hariList = $lokasiHari[$lokasi->nama_lokasi] ?? [1, 2, 3, 4, 5]; // Default: Senin–Jumat
    
            foreach ($hariList as $hari) {
               HariOperasional::create([
                    'lokasi_id' => $lokasi->id,
                    'hari_operasional' => $hari,
                    'is_disabled' => false,
                ]);
            }
        }
    }    

}
