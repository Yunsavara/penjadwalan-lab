<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu
        DB::table('jenislabs')->truncate();

        $data = [];
        for ($i = 1; $i <= 10000; $i++) {
            $data[] = [
                'name' => 'Lab ' . $i,
                'description' => 'Deskripsi untuk Lab Panjang Nih Buat Liat Text Truncate ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('jenislabs')->insert($data);
    }
}
