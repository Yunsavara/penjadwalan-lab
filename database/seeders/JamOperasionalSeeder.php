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
        $hariJamMap = [
            1 => ['08:00:00', '17:00:00'],
            2 => ['08:00:00', '17:00:00'],
            3 => ['08:00:00', '17:00:00'],
            4 => ['08:00:00', '17:00:00'],
            5 => ['08:00:00', '17:00:00'],
            6 => ['08:00:00', '12:00:00'],
            0 => ['08:00:00', '12:00:00'],
        ];

        $hariOperasionals = HariOperasional::all();

        foreach ($hariOperasionals as $hari) {
            $namaHari = strtolower($hari->hari_operasional);

            if (!isset($hariJamMap[$namaHari])) {
                continue; // Lewati kalau tidak ada jam operasional (contoh: Minggu)
            }

            [$startTime, $endTime] = $hariJamMap[$namaHari];
            $start = \Carbon\Carbon::createFromFormat('H:i:s', $startTime);
            $end = \Carbon\Carbon::createFromFormat('H:i:s', $endTime);

            while ($start->lt($end)) {
                JamOperasional::create([
                    'hari_operasional_id' => $hari->id,
                    'jam_mulai' => $start->format('H:i:s'),
                    'jam_selesai' => $start->copy()->addHour()->format('H:i:s'),
                    'is_disabled' => false,
                ]);

                $start->addHour();
            }
        }
    }

}
