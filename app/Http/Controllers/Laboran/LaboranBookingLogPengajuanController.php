<?php

namespace App\Http\Controllers\Laboran;

use App\Models\BookingLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaboranBookingLogPengajuanController extends Controller
{
    public function getDataBookingLog(Request $request)
    {
        $bookingLogs = BookingLog::with(['bookingDetail.laboratorium', 'user']) // Mengambil data lab dan user
                        ->join('booking_details', 'booking_logs.booking_detail_id', '=', 'booking_details.id')
                        ->join('laboratorium_unpams', 'booking_details.lab_id', '=', 'laboratorium_unpams.id')  // Gabungkan dengan tabel laboratorium_unpams
                        ->join('users', 'booking_logs.user_id', '=', 'users.id') // Gabungkan dengan tabel users
                        ->select('booking_logs.*', 'laboratorium_unpams.name as lab_name', 'users.name as user_name') // Memilih kolom yang dibutuhkan
                        ->orderBy('booking_logs.created_at', 'desc')
                        ->get();


        // Menyusun data untuk response json
        $data = $bookingLogs->map(function ($log) {
            return [
                'kode_pengajuan' => $log->bookingDetail->kode_pengajuan,  // Asumsi ada relasi di model BookingDetail
                'lab' => $log->lab_name,  // Nama lab yang diambil dari join
                'user_id' => $log->user_name,  // Nama user yang diambil dari join
                'catatan' => $log->catatan,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),  // Format tanggal
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }

}
