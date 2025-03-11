<?php

namespace App\Http\Controllers\AllRole;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BookingDetail;
use App\Models\WaktuOperasional;
use App\Models\LaboratoriumUnpam;
use App\Http\Controllers\Controller;

class GenerateJadwalController extends Controller
{
    public function generateJadwal(Request $request)
    {
        $today = now()->toDateString();
        $endOfYear = now()->endOfYear()->toDateString();

        // Ambil semua waktu operasional
        $waktuOperasionals = WaktuOperasional::with('lokasi')->get();

        // Ambil semua laboratorium
        $laboratorium = LaboratoriumUnpam::with('jenislab', 'lokasi')->get();

        // Ambil semua booking yang sudah diterima dalam rentang waktu tersebut
        $bookings = BookingDetail::where('status', 'diterima')
            ->whereBetween('tanggal', [$today, $endOfYear])
            ->get();

        $jadwal = [];

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        // Loop dari hari ini sampai akhir tahun
        $currentDate = now();
        $count = 0;
        while ($currentDate->toDateString() <= $endOfYear) {
            $hari = $currentDate->locale('id')->translatedFormat('l'); // Menghasilkan nama hari dalam bahasa Indonesia
            $tanggal = $currentDate->toDateString();

            foreach ($waktuOperasionals as $waktu) {
                if (!in_array($hari, $waktu->hari_operasional)) {
                    continue;
                }

                $lokasi = $waktu->lokasi->name;
                $labs = $laboratorium->where('lokasi_id', $waktu->lokasi->id);

                foreach ($labs as $lab) {
                    // Ambil semua booking untuk lab ini di tanggal tertentu
                    $bookedSlots = $bookings->where('lab_id', $lab->id)
                        ->where('tanggal', $tanggal)
                        ->map(function ($b) {
                            return [
                                'jam_mulai' => $b->jam_mulai,
                                'jam_selesai' => $b->jam_selesai
                            ];
                        })
                        ->sortBy('jam_mulai')
                        ->values();

                    $startTime = $waktu->jam_mulai;
                    $endTime = $waktu->jam_selesai;

                    foreach ($bookedSlots as $booked) {
                        if ($startTime < $booked['jam_mulai']) {
                            $jadwal[] = [
                                'tanggal' => $tanggal,
                                'lokasi' => $lokasi,
                                'ruang_lab' => $lab->name,
                                'jenis_lab' => $lab->jenislab->name,
                                'jam_mulai' => $startTime,
                                'jam_selesai' => $booked['jam_mulai'],
                                'status' => 'tersedia'
                            ];
                        }

                        $jadwal[] = [
                            'tanggal' => $tanggal,
                            'lokasi' => $lokasi,
                            'ruang_lab' => $lab->name,
                            'jenis_lab' => $lab->jenislab->name,
                            'jam_mulai' => $booked['jam_mulai'],
                            'jam_selesai' => $booked['jam_selesai'],
                            'status' => 'dipesan'
                        ];

                        $startTime = $booked['jam_selesai'];
                    }

                    if ($startTime < $endTime) {
                        $jadwal[] = [
                            'tanggal' => $tanggal,
                            'lokasi' => $lokasi,
                            'ruang_lab' => $lab->name,
                            'jenis_lab' => $lab->jenislab->name,
                            'jam_mulai' => $startTime,
                            'jam_selesai' => $endTime,
                            'status' => 'tersedia'
                        ];
                    }
                }
            }

            $currentDate->addDay();
        }

        // Implementasi pagination
        $totalFiltered = count($jadwal);
        $filteredJadwal = array_slice($jadwal, $start, $length);

        // Return the data for DataTables
        return response()->json([
            "draw" => $request->input('draw', 1),
            "recordsTotal" => count($jadwal),
            "recordsFiltered" => $totalFiltered,
            "data" => $filteredJadwal
        ]);
    }

}
