<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaktuOperasional;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WaktuOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('waktu_operasionals')->insert([
            [
                'hari_operasional' => json_encode(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '17:00:00',
                'lokasi_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hari_operasional' => json_encode(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '15:00:00',
                'lokasi_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hari_operasional' => json_encode(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '15:00:00',
                'lokasi_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
