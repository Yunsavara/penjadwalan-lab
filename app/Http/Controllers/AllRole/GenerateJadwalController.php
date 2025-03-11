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
    public function generateJadwal()
    {
        $waktuOperasionals = WaktuOperasional::with('lokasi')->get();
        $laboratorium = LaboratoriumUnpam::with('jenislab', 'lokasi')->get();
        $bookings = BookingDetail::where('status', 'diterima')
            ->select('lab_id', 'tanggal', 'jam_mulai', 'jam_selesai')
            ->get()
            ->toArray(); // Pastikan menjadi array

        return response()->json([
            'waktu_operasional' => $waktuOperasionals,
            'laboratorium' => $laboratorium,
            'bookings' => $bookings
        ]);
    }
}
