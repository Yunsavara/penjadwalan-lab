<?php

namespace Database\Seeders;

use App\Models\HariOperasional;
use App\Models\JamOperasional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hariOperasionals = HariOperasional::all();

        foreach ($hariOperasionals as $hari) {
            // Contoh sesi jam: 08:00 - 10:00, 10:00 - 12:00, 13:00 - 15:00
            $sesiJam = [
                ['08:00:00', '10:00:00'],
                ['10:00:00', '12:00:00'],
                ['13:00:00', '15:00:00'],
            ];

            foreach ($sesiJam as [$mulai, $selesai]) {
                JamOperasional::create([
                    'hari_operasional_id' => $hari->id,
                    'jam_mulai' => $mulai,
                    'jam_selesai' => $selesai,
                    'is_disabled' => false, // Default aktif semua
                ]);
            }
        }
    }
}
