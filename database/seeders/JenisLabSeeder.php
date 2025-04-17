<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JenisLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_jenis_lab' => 'Pemrograman', 'deskripsi_jenis_lab' => 'Lab untuk pengembangan perangkat lunak'],
            ['nama_jenis_lab' => 'Jaringan', 'deskripsi_jenis_lab' => 'Lab untuk konfigurasi dan manajemen jaringan'],
            ['nama_jenis_lab' => 'Elektro', 'deskripsi_jenis_lab' => 'Lab untuk eksperimen elektronika dan listrik'],
            ['nama_jenis_lab' => 'Multimedia', 'deskripsi_jenis_lab' => 'Lab untuk pengembangan design multimedia'],
        ];

        DB::table('jenislabs')->insert($data);
    }
}
