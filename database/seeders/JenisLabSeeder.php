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
            ['name' => 'Pemrograman', 'slug' => Str::slug('Pemrograman'), 'description' => 'Lab untuk pengembangan perangkat lunak'],
            ['name' => 'Jaringan', 'slug' => Str::slug('Jaringan'), 'description' => 'Lab untuk konfigurasi dan manajemen jaringan'],
            ['name' => 'Elektro', 'slug' => Str::slug('Elektro'), 'description' => 'Lab untuk eksperimen elektronika dan listrik'],
            ['name' => 'Multimedia', 'slug' => Str::slug('Multimedia'), 'description' => 'Lab untuk pengembangan design multimedia'],
        ];
        

        DB::table('jenislabs')->insert($data);
    }
}
