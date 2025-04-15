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
            ['name_jenis_lab' => 'Pemrograman', 'slug_jenis_lab' => Str::slug('Pemrograman'), 'description_jenis_lab' => 'Lab untuk pengembangan perangkat lunak'],
            ['name_jenis_lab' => 'Jaringan', 'slug_jenis_lab' => Str::slug('Jaringan'), 'description_jenis_lab' => 'Lab untuk konfigurasi dan manajemen jaringan'],
            ['name_jenis_lab' => 'Elektro', 'slug_jenis_lab' => Str::slug('Elektro'), 'description_jenis_lab' => 'Lab untuk eksperimen elektronika dan listrik'],
            ['name_jenis_lab' => 'Multimedia', 'slug_jenis_lab' => Str::slug('Multimedia'), 'description_jenis_lab' => 'Lab untuk pengembangan design multimedia'],
        ];

        DB::table('jenislabs')->insert($data);
    }
}
