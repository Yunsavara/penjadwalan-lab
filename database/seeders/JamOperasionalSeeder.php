<?php

namespace Database\Seeders;

use App\Models\HariOperasional;
use App\Models\JamOperasional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hariJamMap = [
            1 => ['08:00:00', '17:00:00'], // Senin
            2 => ['08:00:00', '17:00:00'], // Selasa
            3 => ['08:00:00', '17:00:00'], // Rabu
            4 => ['08:00:00', '17:00:00'], // Kamis
            5 => ['08:00:00', '17:00:00'], // Jumat
            6 => ['08:00:00', '12:00:00'], // Sabtu
            0 => ['08:00:00', '09:00:00'], // Minggu
        ];

        $hariOperasionals = HariOperasional::all();

        foreach ($hariOperasionals as $hari) {
            $namaHari = strtolower($hari->hari_operasional);

            if (!isset($hariJamMap[$namaHari])) {
                continue; // Lewati kalau tidak ada jam operasional (contoh: Minggu)
            }

            [$startTime, $endTime] = $hariJamMap[$namaHari];
            $start = Carbon::createFromFormat('H:i:s', $startTime);
            $end = Carbon::createFromFormat('H:i:s', $endTime);

            // Loop untuk membuat jam operasional per jam
            while ($start->lt($end)) {
                // Format jam mulai dan jam selesai untuk masing-masing jam
                $jamSelesai = $start->copy()->addMinute(59)->addSecond(59);

                JamOperasional::create([
                    'hari_operasional_id' => $hari->id,
                    'jam_mulai' => $start->format('H:i:s'),
                    'jam_selesai' => $jamSelesai->format('H:i:s'),
                    'is_disabled' => false,
                ]);

                // Tambahkan satu jam ke waktu mulai
                $start->addHour();
            }
        }
    }
}
