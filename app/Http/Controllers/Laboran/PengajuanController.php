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
                'page' => 'Jadwal'
            ]
        ]);
    }

    public function getDataJadwal(Request $request)
    {
        $query = Jadwal::select('kode_pengajuan', 'keperluan', 'status', 'lab_id');

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->where('kode_pengajuan', 'like', "%$search%")
                ->orWhere('keperluan', 'like', "%$search%");
        }

        // Clone query untuk menghitung total record setelah filtering
        $recordsFiltered = $query->count();

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination (tanpa filter)
        $recordsTotal = Jadwal::count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    // Datatables Pengajuan
    public function getDataPengajuan(Request $request)
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
                    'changed_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            StatusPengajuanHistories::insert($historyData);

            // Jika status diubah menjadi "diterima", masukkan ke tabel `jadwals`
            if ($statusBaru === "diterima") {
                $jadwalData = [];
                $historyTergantikan = [];

                foreach ($pengajuans as $pengajuan) {
                    // Ambil priority dari user yang mengajukan
                    $pengajuanUser = $pengajuan->user;
                    $pengajuanPriority = $pengajuanUser->role->priority;

                    // Cek apakah ada jadwal yang bentrok
                    $jadwalBentrok = Jadwal::where('lab_id', $pengajuan->lab_id)
                        ->where('tanggal', $pengajuan->tanggal)
                        ->where('jam_mulai', '<', $pengajuan->jam_selesai)
                        ->where('jam_selesai', '>', $pengajuan->jam_mulai)
                        ->whereIn('status', ['belum digunakan'])
                        ->first();

                    if ($jadwalBentrok) {
                        $existingUser = $jadwalBentrok->user;
                        $existingPriority = $existingUser->role->priority;

                        // Cek priority antara user pengajuan dan user jadwal yang bentrok
                        if ($pengajuanPriority > $existingPriority) {
                            DB::rollBack();
                            return redirect()->back()->with('error', "Pengajuan bentrok dengan jadwal lain yang memiliki prioritas lebih tinggi.");
                        }

                        // Jika pengajuan baru lebih tinggi priority-nya, ubah jadwal lama jadi "tergantikan"
                        $jadwalBentrok->update(['status' => 'tergantikan']);

                        // Simpan perubahan status "tergantikan" ke history
                        $historyTergantikan[] = [
                            'kode_pengajuan' => $jadwalBentrok->kode_pengajuan,
                            'tanggal' => $jadwalBentrok->tanggal,
                            'jam_mulai' => $jadwalBentrok->jam_mulai,
                            'jam_selesai' => $jadwalBentrok->jam_selesai,
                            'status' => 'tergantikan',
                            'user_id' => $jadwalBentrok->user_id,
                            'lab_id' => $jadwalBentrok->lab_id,
                            'changed_by' => auth()->id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // Masukkan pengajuan baru sebagai jadwal
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

                // Insert history untuk jadwal yang "tergantikan"
                if (!empty($historyTergantikan)) {
                    StatusPengajuanHistories::insert($historyTergantikan);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Status berhasil diubah menjadi {$statusBaru}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }


}
