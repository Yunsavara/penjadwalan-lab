<?php

namespace App\Http\Controllers\AllRole;

use App\Models\Pengajuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AllRole\PengajuanStoreRequest;
use App\Models\DetailPengajuan;

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
        // dd($request->all());
        try {
            DB::beginTransaction();

            // Generate kode pengajuan unik
            $kodePengajuan = 'PJ-' . Str::upper(Str::random(6));

            // Simpan data ke tabel `pengajuans`
            $pengajuan = Pengajuan::create([
                'kode_pengajuan' => $kodePengajuan,
                'keperluan' => $request->keperluan,
                'user_id' => $request->user_id,
                'lab_id' => $request->lab_id,
                'status' => 'pending',
            ]);

            // Simpan data ke tabel `jadwals` untuk setiap tanggal
            foreach ($request->tanggal_pengajuan as $tanggal) {
                if (!isset($request->jam_mulai[$tanggal]) || !isset($request->jam_selesai[$tanggal])) {
                    continue; // Lewati jika jam mulai/selesai tidak ada untuk tanggal ini
                }

                DetailPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $request->jam_mulai[$tanggal],
                    'jam_selesai' => $request->jam_selesai[$tanggal],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengajuan gagal diajukan!'. $e->getMessage());
        }
    }

    // Untuk Datatables
    public function getData(Request $request)
    {
        // Menyaring data berdasarkan pencarian jika ada
        $query = Pengajuan::query()->select([
                    'id',
                    'kode_pengajuan',
                    'keperluan',
                    'status',
                    'lab_id',
                    'user_id'
                ]);


        if ($search = $request->input('search.value')) {
            $query->where('kode_pengajuan', 'like', "%$search%")
                  ->orWhere('keperluan', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = Pengajuan::count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    // Detail Modal
    public function getDetailPengajuan($kodePengajuan)
    {
        $pengajuan = Pengajuan::where('kode_pengajuan', $kodePengajuan)
            ->with('detailPengajuans')
            ->first();

        if (!$pengajuan) {
            return response()->json([
                'error' => 'Pengajuan tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'details' => $pengajuan->detailPengajuans
        ]);
    }

}
