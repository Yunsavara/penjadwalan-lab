<?php

namespace App\Http\Controllers\AllRole;

use Carbon\Carbon;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    //Datatables jadwal
    public function getDataJadwal(Request $request)
    {
        $user_id = auth()->id();

        // Ambil data dari `booking_details` berdasarkan user yang sedang login
        $query = BookingDetail::with('laboratorium') ->whereHas('booking', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })
        ->whereNotIn('status', ['pending', 'ditolak']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('keperluan', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('kode_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('laboratorium', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Total data sebelum paginasi
        $totalData = $query->count();

        // Paginate manual untuk server-side
        $start = $request->start;
        $length = $request->length ?? 10;
        $data = $query->skip($start)->take($length)->get();

        // Format data untuk DataTables
        $result = [];
        foreach ($data as $index => $jadwal) {
            $result[] = [
                'id' => $jadwal->id,
                'index' => $start + $index + 1,
                'kode_pengajuan' => $jadwal->kode_pengajuan,
                'lab' => $jadwal->laboratorium->name ?? '-',
                'tanggal' => Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d F Y'),
                'jam_mulai' => Carbon::parse($jadwal->jam_mulai)->format('h:i A'),
                'jam_selesai' => Carbon::parse($jadwal->jam_selesai)->format('h:i A'),
                'keperluan' => $jadwal->keperluan,
                'status' => ucfirst($jadwal->status),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData, // Bisa diubah jika pakai filter
            'data' => $result
        ]);
    }

    // Detail Modal
    public function getDetailJadwal($kode_pengajuan)
    {
        // Ambil booking details berdasarkan kode_pengajuan
        $details = BookingDetail::with(['laboratorium', 'bookingLog.user'])
            ->whereHas('booking', function ($query) use ($kode_pengajuan) {
                $query->where('kode_pengajuan', $kode_pengajuan);
            })
            ->get();

        // Jika tidak ditemukan
        if ($details->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        // Format data untuk dikirim ke frontend
        $formattedDetails = $details->map(function ($detail) {
            return [
                'lab' => $detail->laboratorium->name,
                'tanggal' => $detail->tanggal,
                'jam_mulai' => $detail->jam_mulai,
                'jam_selesai' => $detail->jam_selesai,
                'status' => ucfirst($detail->status),
            ];
        });

        // Ambil logs untuk setiap bookingDetail
        $logs = $details->flatMap(function ($detail) {
            return $detail->bookingLog->map(function ($log) {
                return [
                    'status' => ucfirst($log->status),
                    'user' => $log->user->email ?? 'System',
                    'waktu' => $log->created_at->format('Y-m-d H:i:s'),
                    'catatan' => $log->catatan ?? '-',
                ];
            });
        });

        return response()->json([
            'success' => true,
            'data' => [
                'kode_pengajuan' => $kode_pengajuan,
                'details' => $formattedDetails,
                'logs' => $logs, // Kirim log ke frontend
            ]
        ]);
    }

    public function batalkanJadwal(Request $request)
    {
        $request->validate([
            'booking_detail_id' => 'required|exists:booking_details,id',
        ]);

        $user_id = Auth::id();
        $bookingDetailId = $request->booking_detail_id;

        try {
            DB::beginTransaction();

            // Ambil detail booking berdasarkan ID dan user yang mengajukan
            $detail = BookingDetail::where('id', $bookingDetailId)
                ->whereHas('booking', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })
                ->where('status', 'diterima') // Hanya bisa membatalkan yang diterima
                ->first();

            if (!$detail) {
                return redirect()->back()->with('error', 'Jadwal tidak ditemukan atau tidak dapat dibatalkan.');
            }

            // Ubah status menjadi "dibatalkan"
            $detail->update(['status' => 'dibatalkan']);

            // Tambahkan log pembatalan
            BookingLog::create([
                'booking_detail_id' => $detail->id,
                'user_id' => $user_id,
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan oleh pengguna.',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jadwal berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan jadwal.');
        }
    }


    // public function batalkanJadwal(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $kodePengajuan = $request->kode_pengajuan;

    //         // Ambil semua pengajuan dengan kode yang sama
    //         $jadwals = Jadwal::where('kode_pengajuan', $kodePengajuan)->get();

    //         if ($jadwals->isEmpty()) {
    //             return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
    //         }

    //         // Update status pengajuan menjadi "dibatalkan"
    //         Jadwal::where('kode_pengajuan', $kodePengajuan)->update(['status' => 'dibatalkan']);

    //         // Simpan perubahan ke dalam `pengajuan_status_histories`
    //         $historyData = [];
    //         foreach ($jadwals as $jadwal) {
    //             $historyData[] = [
    //                 'kode_pengajuan' => $kodePengajuan,
    //                 'tanggal' => $jadwal->tanggal,
    //                 'jam_mulai' => $jadwal->jam_mulai,
    //                 'jam_selesai' => $jadwal->jam_selesai,
    //                 'status' => 'dibatalkan',
    //                 'user_id' => $jadwal->user_id,
    //                 'lab_id' => $jadwal->lab_id,
    //                 'changed_by' => auth()->id(),
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    //         StatusPengajuanHistories::insert($historyData);

    //         DB::commit();
    //         return redirect()->back()->with('success', "Pengajuan berhasil dibatalkan.");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
    //     }
    // }

}
