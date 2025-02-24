<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Jadwal;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StatusPengajuanHistories;

class PengajuanController extends Controller
{
    public function index(){
        return view('laboran.pengajuan.pengajuan', [
            'page_meta' => [
                'page' => 'Pengajuan'
            ]
        ]);
    }

    // Datatables
    public function getData(Request $request)
    {
        // Query untuk mengambil data unik berdasarkan kode_pengajuan
        $query = Pengajuan::select([
                    'kode_pengajuan',
                    DB::raw('GROUP_CONCAT(DISTINCT tanggal ORDER BY tanggal ASC SEPARATOR ", ") as tanggal_pengajuan'),
                    'keperluan',
                    'status',
                    'lab_id',
                    'user_id'
                ])
                ->groupBy('kode_pengajuan', 'keperluan', 'status', 'lab_id', 'user_id');

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->having('kode_pengajuan', 'like', "%$search%")
                ->orHaving('keperluan', 'like', "%$search%")
                ->orHaving('tanggal_pengajuan', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = Pengajuan::distinct('kode_pengajuan')->count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    public function updateStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            $kodePengajuan = $request->kode_pengajuan;
            $statusBaru = $request->status;
            $user = auth()->user();

            // Ambil semua pengajuan dengan kode yang sama
            $pengajuans = Pengajuan::where('kode_pengajuan', $kodePengajuan)->get();

            if ($pengajuans->isEmpty()) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan.');
            }

            // Update status pengajuan
            Pengajuan::where('kode_pengajuan', $kodePengajuan)->update(['status' => $statusBaru]);

            // Simpan perubahan ke dalam `pengajuan_status_histories`
            $historyData = [];
            foreach ($pengajuans as $pengajuan) {
                $historyData[] = [
                    'kode_pengajuan' => $kodePengajuan,
                    'tanggal' => $pengajuan->tanggal,
                    'jam_mulai' => $pengajuan->jam_mulai,
                    'jam_selesai' => $pengajuan->jam_selesai,
                    'status' => $statusBaru,
                    'user_id' => $pengajuan->user_id,
                    'lab_id' => $pengajuan->lab_id,
                    'changed_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            StatusPengajuanHistories::insert($historyData);

            // Jika status diubah menjadi "diterima", masukkan ke tabel `jadwals`
            if ($statusBaru === "diterima") {
                $jadwalData = [];
                foreach ($pengajuans as $pengajuan) {
                    $jadwalData[] = [
                        'kode_pengajuan' => $kodePengajuan,
                        'keperluan' => $pengajuan->keperluan,
                        'tanggal' => $pengajuan->tanggal,
                        'jam_mulai' => $pengajuan->jam_mulai,
                        'jam_selesai' => $pengajuan->jam_selesai,
                        'status' => 'belum digunakan',
                        'lab_id' => $pengajuan->lab_id,
                        'user_id' => $pengajuan->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Jadwal::insert($jadwalData);
            }

            DB::commit();
            return redirect()->back()->with('success', "Status berhasil diubah menjadi {$statusBaru}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

}
