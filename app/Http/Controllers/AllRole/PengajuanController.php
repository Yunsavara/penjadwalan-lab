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
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $kodePengajuan = 'PJ-' . strtoupper(Str::random(6));

            foreach ($request->tanggal_pengajuan as $tanggal) {
                // Pastikan jam_mulai dan jam_selesai ada dan sesuai index tanggal
                if (!isset($request->jam_mulai[$tanggal]) || !isset($request->jam_selesai[$tanggal])) {
                    throw new \Exception("Jam mulai atau jam selesai tidak ditemukan untuk tanggal {$tanggal}.");
                }

                $jamMulaiBaru = $request->jam_mulai[$tanggal];
                $jamSelesaiBaru = $request->jam_selesai[$tanggal];

                // **Cek bentrok dengan status "pending", "diterima", atau "sedang dipakai"**
                $bentrok = StatusPengajuanHistories::where('user_id', $user->id)
                    ->where('lab_id', $request->lab_id)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', ['pending', 'diterima', 'sedang dipakai']) // Hanya cek yang aktif
                    ->where(function ($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                        $query->where(function ($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                            $q->where('jam_mulai', '<', $jamSelesaiBaru)
                            ->where('jam_selesai', '>', $jamMulaiBaru);
                        });
                    })
                    ->get();

                if ($bentrok->isNotEmpty()) {
                    // Ambil daftar jam yang menyebabkan bentrok dan statusnya
                    $bentrokJadwal = $bentrok->map(function ($item) {
                        return $item->jam_mulai . ' - ' . $item->jam_selesai . " (Status: {$item->status})";
                    })->implode(', ');

                    DB::rollBack();
                    return redirect()->route('pengajuan')->with(
                        'error',
                        "Pengajuan pada tanggal {$tanggal} <br> bentrok dengan pengajuan anda yang sudah ada:<br> {$bentrokJadwal}."
                    );
                }

                // Simpan ke tabel pengajuans
                $pengajuan = Pengajuan::create([
                    'kode_pengajuan' => $kodePengajuan,
                    'user_id' => $user->id,
                    'lab_id' => $request->lab_id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulaiBaru,
                    'jam_selesai' => $jamSelesaiBaru,
                    'keperluan' => $request->keperluan,
                    'status' => 'pending', // Default status saat diajukan
                ]);

                // Simpan ke tabel pengajuan_status_histories
                StatusPengajuanHistories::create([
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulaiBaru,
                    'jam_selesai' => $jamSelesaiBaru,
                    'status' => 'pending',
                    'user_id' => $user->id,
                    'lab_id' => $request->lab_id,
                    'changed_by' => $user->id, // User sendiri yang mengajukan
                ]);
            }

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengajuan')->with('error', 'Pengajuan gagal dikirim: ' . $e->getMessage());
        }
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

    // Detail Modal
    public function getDetail($kode_pengajuan)
    {
        $pengajuan = Pengajuan::with('laboratorium')
            ->where('kode_pengajuan', $kode_pengajuan)
            ->first();

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $histories = StatusPengajuanHistories::where('kode_pengajuan', $kode_pengajuan)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($history) {
                return [
                    'tanggal' => $history->tanggal,
                    'jam_mulai' => $history->jam_mulai,
                    'jam_selesai' => $history->jam_selesai,
                    'status' => $history->status,
                    'changed_by_name' => User::find($history->changed_by)->name ?? 'Unknown',
                    'created_at' => $history->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'pengajuan' => [
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'laboratorium' => [
                        'id' => $pengajuan->lab_id,
                        'name' => $pengajuan->laboratorium->name,
                    ],
                    'keperluan' => $pengajuan->keperluan,
                    'jadwal' => Pengajuan::where('kode_pengajuan', $kode_pengajuan)
                        ->get(['tanggal', 'jam_mulai', 'jam_selesai']),
                ],
                'histories' => $histories,
            ]
        ]);
    }

}
