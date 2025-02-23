<?php

namespace App\Http\Controllers\AllRole;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPengajuan;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StatusPengajuanHistories;
use App\Http\Requests\AllRole\PengajuanStoreRequest;

class PengajuanController extends Controller
{
    public function index(){
        $laboratorium = LaboratoriumUnpam::with('Jenislab') // Memuat relasi Jenislab
        ->select('id', 'jenislab_id', 'name', 'lokasi', 'kapasitas')
        ->get();
        return view('all-role.pengajuan', [
            'Ruangan' => $laboratorium,
            'page_meta' => [
                'page' => 'Pengajuan',
            ]
        ]);
    }

    public function store(PengajuanStoreRequest $request)
    {
        // Check Ke Jadwal Belum
        DB::beginTransaction();
        try {
            $kodePengajuan = 'PJ-' . strtoupper(Str::random(8));

            foreach ($request->tanggal_pengajuan as $tanggal) {
                // Cek apakah ada bentrok di database
                $bentrok = Pengajuan::where('tanggal', $tanggal)
                    ->where('lab_id', $request->lab_id)
                    ->where('user_id', Auth::id()) // Hanya cek untuk user yang login
                    ->whereIn('status', ['pending', 'diterima']) // Hanya cek pengajuan aktif
                    ->where(function ($query) use ($request, $tanggal) {
                        $query->whereBetween('jam_mulai', [$request->jam_mulai[$tanggal], $request->jam_selesai[$tanggal]])
                            ->orWhereBetween('jam_selesai', [$request->jam_mulai[$tanggal], $request->jam_selesai[$tanggal]])
                            ->orWhere(function ($q) use ($request, $tanggal) {
                                $q->where('jam_mulai', '<=', $request->jam_mulai[$tanggal])
                                    ->where('jam_selesai', '>=', $request->jam_selesai[$tanggal]);
                            });
                    })
                    ->exists();

                if ($bentrok) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jadwal pada tanggal $tanggal bentrok dengan pengajuan lain!");
                }

                // Simpan pengajuan jika tidak bentrok
                $pengajuan = Pengajuan::create([
                    'kode_pengajuan' => $kodePengajuan,
                    'keperluan' => $request->keperluan,
                    'status' => 'pending',
                    'tanggal' => $tanggal,
                    'jam_mulai' => $request->jam_mulai[$tanggal],
                    'jam_selesai' => $request->jam_selesai[$tanggal],
                    'lab_id' => $request->lab_id,
                    'user_id' => Auth::id(),
                ]);

                // Simpan tracking status pengajuan
                StatusPengajuanHistories::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

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


    // Detail Modal
    public function getDetail($kode_pengajuan)
    {
        $pengajuan = Pengajuan::where('kode_pengajuan', $kode_pengajuan)->first();

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
        }

        // Ambil semua jadwal yang berkaitan dengan kode_pengajuan
        $jadwal = Pengajuan::where('kode_pengajuan', $kode_pengajuan)
                    ->select('tanggal', 'jam_mulai', 'jam_selesai')
                    ->get();

        // Ambil tracking status pengajuan
        $statusHistori = StatusPengajuanHistories::where('pengajuan_id', $pengajuan->id)
                            ->select('status', 'created_at')
                            ->orderBy('created_at', 'asc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'keperluan' => $pengajuan->keperluan,
                'lab_id' => $pengajuan->lab_id,
                'jadwal' => $jadwal,
                'status_histori' => $statusHistori
            ]
        ]);
    }


}
