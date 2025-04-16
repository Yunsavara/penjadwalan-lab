<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratoriumUnpamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labs = [
            ['nama_laboratorium' => 'CBT 1', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 2', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 3', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 4', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 5', 'lokasi_id' => 4, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 6', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 7', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'CBT 8', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 30, 'jenislab_id' => 1],

            ['nama_laboratorium' => 'TI 1', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 25, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'TI 2', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 25, 'jenislab_id' => 1],
            ['nama_laboratorium' => 'TI 3', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 25, 'jenislab_id' => 1],

            ['nama_laboratorium' => 'Jaringan 1', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 20, 'jenislab_id' => 2],
            ['nama_laboratorium' => 'Jaringan 2', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 20, 'jenislab_id' => 2],
            ['nama_laboratorium' => 'Jaringan 3', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 20, 'jenislab_id' => 2],

            ['nama_laboratorium' => 'Elektro 1', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 15, 'jenislab_id' => 3],
            ['nama_laboratorium' => 'Elektro 2', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 15, 'jenislab_id' => 3],
            ['nama_laboratorium' => 'Elektro 3', 'lokasi_id' => 2, 'kapasitas_laboratorium' => 15, 'jenislab_id' => 3],

            ['nama_laboratorium' => 'Multimedia 1', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 25, 'jenislab_id' => 4],
            ['nama_laboratorium' => 'Multimedia 2', 'lokasi_id' => 3, 'kapasitas_laboratorium' => 25, 'jenislab_id' => 4],
        ];

        foreach ($labs as $lab) {
            DB::table('laboratorium_unpams')->insert([
                'nama_laboratorium' => $lab['nama_laboratorium'],
                'lokasi_id' => $lab['lokasi_id'],
                'kapasitas_laboratorium' => $lab['kapasitas_laboratorium'],
                'status_laboratorium' => 'tersedia',
                'jenislab_id' => $lab['jenislab_id'],
                'deskripsi_laboratorium' => 'Laboratorium ' . $lab['nama_laboratorium'] . ' digunakan untuk praktikum dan ujian sesuai dengan jenis lab-nya.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

}
