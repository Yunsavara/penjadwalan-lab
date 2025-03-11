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

        // Pencarian dan pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');

        // Loop dari hari ini sampai akhir tahun
        $currentDate = now();
        while ($currentDate->toDateString() <= $endOfYear) {
            $hari = $currentDate->locale('id')->translatedFormat('l'); // Menghasilkan nama hari dalam bahasa Indonesia
            $tanggal = $currentDate->toDateString(); // Format tanggal untuk pencarian (Y-m-d)

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
                                'jam_mulai' => Carbon::parse($b->jam_mulai),
                                'jam_selesai' => Carbon::parse($b->jam_selesai)
                            ];
                        })
                        ->sortBy('jam_mulai')
                        ->values();

                    $startTime = Carbon::parse($waktu->jam_mulai);
                    $endTime = Carbon::parse($waktu->jam_selesai);

                    // Menambahkan slot yang tersedia di antara jadwal yang sudah dipesan
                    foreach ($bookedSlots as $booked) {
                        // Slot tersedia sebelum jam mulai booking
                        if ($startTime < $booked['jam_mulai']) {
                            $jadwal[] = [
                                'tanggal' => $currentDate->locale('id')->translatedFormat('d F Y'), // Format tanggal untuk tampilan
                                'lokasi' => $lokasi,
                                'ruang_lab' => $lab->name,
                                'jenis_lab' => $lab->jenislab->name,
                                'jam_mulai' => $startTime->format('h:i A'), // Format AM/PM
                                'jam_selesai' => $booked['jam_mulai']->format('h:i A'), // Format AM/PM
                                'status' => 'tersedia'
                            ];
                        }

                        // Jadwal yang sudah dipesan
                        $jadwal[] = [
                            'tanggal' => $currentDate->locale('id')->translatedFormat('d F Y'), // Format tanggal untuk tampilan
                            'lokasi' => $lokasi,
                            'ruang_lab' => $lab->name,
                            'jenis_lab' => $lab->jenislab->name,
                            'jam_mulai' => $booked['jam_mulai']->format('h:i A'), // Format AM/PM
                            'jam_selesai' => $booked['jam_selesai']->format('h:i A'), // Format AM/PM
                            'status' => 'dipesan'
                        ];

                        $startTime = $booked['jam_selesai']; // Update waktu mulai selanjutnya
                    }

                    // Jika ada waktu yang masih tersedia setelah booking yang ada
                    if ($startTime < $endTime) {
                        $jadwal[] = [
                            'tanggal' => $currentDate->locale('id')->translatedFormat('d F Y'), // Format tanggal untuk tampilan
                            'lokasi' => $lokasi,
                            'ruang_lab' => $lab->name,
                            'jenis_lab' => $lab->jenislab->name,
                            'jam_mulai' => $startTime->format('h:i A'), // Format AM/PM
                            'jam_selesai' => $endTime->format('h:i A'), // Format AM/PM
                            'status' => 'tersedia'
                        ];
                    }
                }
            }

            // Tambahkan satu hari lagi
            $currentDate->addDay();
        }

        // Implementasi pencarian
        if ($search) {
            $jadwal = array_filter($jadwal, function($item) use ($search) {
                return strpos(strtolower($item['ruang_lab']), strtolower($search)) !== false ||
                    strpos(strtolower($item['jenis_lab']), strtolower($search)) !== false ||
                    strpos(strtolower($item['lokasi']), strtolower($search)) !== false ||
                    strpos($item['tanggal'], $search) !== false;
            });
        }

        // Implementasi pagination
        $totalFiltered = count($jadwal);
        $filteredJadwal = array_slice($jadwal, $start, $length);

        // Return data untuk datatables
        return response()->json([
            "draw" => $request->input('draw', 1),
            "recordsTotal" => count($jadwal),
            "recordsFiltered" => $totalFiltered,
            "data" => $filteredJadwal
        ]);
    }

}
