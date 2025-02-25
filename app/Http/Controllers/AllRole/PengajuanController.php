<?php

namespace App\Http\Controllers\AllRole;

use Log;
use App\Models\User;
use App\Models\Jadwal;
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
use App\Http\Requests\AllRole\PengajuanUpdateRequest;

class PengajuanController extends Controller
{
    public function index(){
        $laboratorium = LaboratoriumUnpam::with('Jenislab') // Memuat relasi Jenislab
        ->select('id', 'jenislab_id', 'name', 'lokasi', 'kapasitas')
        ->get();
        return view('all-role.pengajuan', [
            'Ruangan' => $laboratorium,
            'page_meta' => [
                'page' => 'Jadwal dan Pengajuan',
            ]
        ]);
    }

    public function store(PengajuanStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $userPriority = $user->role->priority;
            $kodePengajuan = 'PJ-' . strtoupper(Str::random(6));

            $pengajuanData = [];
            $historyData = [];

            foreach ($request->tanggal_pengajuan as $index => $tanggal) {
                $jamMulaiBaru = $request->jam_mulai[$index] ?? null;
                $jamSelesaiBaru = $request->jam_selesai[$index] ?? null;

                if (!$jamMulaiBaru || !$jamSelesaiBaru) {
                    throw new \Exception("Jam mulai atau jam selesai tidak ditemukan untuk tanggal {$tanggal}.");
                }

                $bentrokHistories = StatusPengajuanHistories::where('user_id', $user->id)
                    ->where('lab_id', $request->lab_id)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', ['pending', 'diterima', 'sedang dipakai'])
                    ->where(function ($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                        $query->where(function ($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                            $q->where('jam_mulai', '<', $jamSelesaiBaru)
                                ->where('jam_selesai', '>', $jamMulaiBaru);
                        });
                    })
                    ->get(['kode_pengajuan', 'status']);

                if ($bentrokHistories->isNotEmpty()) {
                    $bentrokInfo = $bentrokHistories->map(function ($item) {
                        return "{$item->kode_pengajuan} (Status: {$item->status})";
                    })->implode(', ');

                    DB::rollBack();
                    return redirect()->route('pengajuan')->withInput()->with(
                        'error',
                        "Pengajuan pada tanggal {$tanggal} bentrok dengan:<br> {$bentrokInfo}."
                    );
                }

                $bentrokJadwal = Jadwal::where('lab_id', $request->lab_id)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', ['belum digunakan', 'sedang digunakan'])
                    ->where(function ($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                        $query->where(function ($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                            $q->where('jam_mulai', '<', $jamSelesaiBaru)
                                ->where('jam_selesai', '>', $jamMulaiBaru);
                        });
                    })
                    ->get();

                if ($bentrokJadwal->isNotEmpty()) {
                    foreach ($bentrokJadwal as $jadwal) {
                        $jadwalPriority = $jadwal->user->role->priority; // Ambil priority dari user yang punya jadwal

                        // dd($jadwal->user->role->priority);

                        if ($userPriority > $jadwalPriority) {
                            DB::rollBack();
                            return redirect()->route('pengajuan')->withInput()->with(
                                'error',
                                "Pengajuan pada tanggal {$tanggal} bentrok dengan jadwal user lain yang memiliki prioritas lebih tinggi."
                            );
                        }
                    }
                }

                $pengajuanData[] = [
                    'kode_pengajuan' => $kodePengajuan,
                    'user_id' => $user->id,
                    'lab_id' => $request->lab_id,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulaiBaru,
                    'jam_selesai' => $jamSelesaiBaru,
                    'keperluan' => $request->keperluan,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $historyData[] = [
                    'kode_pengajuan' => $kodePengajuan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulaiBaru,
                    'jam_selesai' => $jamSelesaiBaru,
                    'status' => 'pending',
                    'user_id' => $user->id,
                    'lab_id' => $request->lab_id,
                    'changed_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Pengajuan::insert($pengajuanData);
            StatusPengajuanHistories::insert($historyData);

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
        $userId = auth()->id(); // Ambil ID user yang sedang login

        // Query untuk mengambil data unik berdasarkan kode_pengajuan milik user login
        $query = Pengajuan::select([
                    'kode_pengajuan',
                    DB::raw('GROUP_CONCAT(DISTINCT tanggal ORDER BY tanggal ASC SEPARATOR ", ") as tanggal_pengajuan'),
                    'keperluan',
                    'status',
                    'lab_id',
                    'user_id'
                ])
                ->where('user_id', $userId) // Filter berdasarkan user yang login
                ->groupBy('kode_pengajuan', 'keperluan', 'status', 'lab_id', 'user_id');

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->having('kode_pengajuan', 'like', "%$search%")
                ->orHaving('keperluan', 'like', "%$search%")
                ->orHaving('tanggal_pengajuan', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination berdasarkan user login
        $recordsTotal = Pengajuan::where('user_id', $userId)->distinct('kode_pengajuan')->count();
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

    public function edit($kode_pengajuan)
    {
        $pengajuan = Pengajuan::where('kode_pengajuan', $kode_pengajuan)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_pengajuan' => $item->kode_pengajuan,
                'keperluan' => $item->keperluan,
                'lab_id' => $item->lab_id,
                'tanggal' => $item->tanggal,
                'jam_mulai' => date('H:i', strtotime($item->jam_mulai)), // Ubah format jam
                'jam_selesai' => date('H:i', strtotime($item->jam_selesai)), // Ubah format jam
            ];
        });

        if ($pengajuan->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!']);
        }

        return response()->json([
            'success' => true,
            'data' => $pengajuan
        ]);
    }


    public function update(PengajuanUpdateRequest $request, $kode_pengajuan)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $existingPengajuan = Pengajuan::where('kode_pengajuan', $kode_pengajuan)->get();
            $historyData = [];

            foreach ($request->tanggal_pengajuan as $index => $tanggal) {
                $jamMulaiBaru = $request->jam_mulai[$index];
                $jamSelesaiBaru = $request->jam_selesai[$index];

                // Cek apakah tanggal ini sudah ada di database
                $pengajuan = $existingPengajuan->where('tanggal', $tanggal)->first();

                // Cek Bentrok
                $bentrok = StatusPengajuanHistories::where('user_id', $user->id)
                    ->where('lab_id', $request->lab_id)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', ['pending', 'diterima', 'sedang dipakai'])
                    ->where(function ($query) use ($jamMulaiBaru, $jamSelesaiBaru) {
                        $query->where(function ($q) use ($jamMulaiBaru, $jamSelesaiBaru) {
                            $q->where('jam_mulai', '<', $jamSelesaiBaru)
                            ->where('jam_selesai', '>', $jamMulaiBaru);
                        });
                    })
                    ->where('kode_pengajuan', '!=', $kode_pengajuan) // Jangan cek bentrok dengan dirinya sendiri
                    ->get(['kode_pengajuan', 'status']);

                if ($bentrok->isNotEmpty()) {
                    $bentrokInfo = $bentrok->map(function ($item) {
                        return "{$item->kode_pengajuan} (Status: {$item->status})";
                    })->implode(', ');

                    DB::rollBack();
                    return redirect()->route('pengajuan')->withInput()->with(
                        'error',
                        "Pengajuan pada tanggal {$tanggal} bentrok dengan:<br> {$bentrokInfo}."
                    );
                }

                if ($pengajuan) {
                    // Jika jam mulai atau jam selesai berubah
                    if ($pengajuan->jam_mulai !== $jamMulaiBaru || $pengajuan->jam_selesai !== $jamSelesaiBaru) {
                        // Simpan histori perubahan dengan status "dibatalkan" untuk data lama
                        $historyData[] = [
                            'kode_pengajuan' => $kode_pengajuan,
                            'tanggal' => $pengajuan->tanggal,
                            'jam_mulai' => $pengajuan->jam_mulai,
                            'jam_selesai' => $pengajuan->jam_selesai,
                            'status' => 'dibatalkan',
                            'user_id' => $pengajuan->user_id,
                            'lab_id' => $pengajuan->lab_id,
                            'changed_by' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // Simpan histori baru dengan status "pending"
                        $historyData[] = [
                            'kode_pengajuan' => $kode_pengajuan,
                            'tanggal' => $tanggal,
                            'jam_mulai' => $jamMulaiBaru,
                            'jam_selesai' => $jamSelesaiBaru,
                            'status' => 'pending',
                            'user_id' => $user->id,
                            'lab_id' => $request->lab_id,
                            'changed_by' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // Update data utama
                    $pengajuan->update([
                        'keperluan' => $request->keperluan,
                        'lab_id' => $request->lab_id,
                        'jam_mulai' => $jamMulaiBaru,
                        'jam_selesai' => $jamSelesaiBaru,
                    ]);
                } else {
                    // Jika tanggal belum ada, tambahkan sebagai data baru
                    Pengajuan::create([
                        'kode_pengajuan' => $kode_pengajuan,
                        'keperluan' => $request->keperluan,
                        'lab_id' => $request->lab_id,
                        'tanggal' => $tanggal,
                        'jam_mulai' => $jamMulaiBaru,
                        'jam_selesai' => $jamSelesaiBaru,
                        'status' => 'pending',
                        'user_id' => $user->id,
                    ]);

                    // Simpan histori untuk tanggal baru
                    $historyData[] = [
                        'kode_pengajuan' => $kode_pengajuan,
                        'tanggal' => $tanggal,
                        'jam_mulai' => $jamMulaiBaru,
                        'jam_selesai' => $jamSelesaiBaru,
                        'status' => 'pending',
                        'user_id' => $user->id,
                        'lab_id' => $request->lab_id,
                        'changed_by' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Hapus tanggal lama yang tidak ada di input baru dan catat ke history
            $submittedDates = $request->tanggal_pengajuan;
            $deletedPengajuan = Pengajuan::where('kode_pengajuan', $kode_pengajuan)
                ->whereNotIn('tanggal', $submittedDates)
                ->get();

            foreach ($deletedPengajuan as $del) {
                $historyData[] = [
                    'kode_pengajuan' => $del->kode_pengajuan,
                    'tanggal' => $del->tanggal,
                    'jam_mulai' => $del->jam_mulai,
                    'jam_selesai' => $del->jam_selesai,
                    'status' => 'dibatalkan',
                    'user_id' => $del->user_id,
                    'lab_id' => $del->lab_id,
                    'changed_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Pengajuan::where('kode_pengajuan', $kode_pengajuan)
                ->whereNotIn('tanggal', $submittedDates)
                ->delete();

            // Simpan histori perubahan
            if (!empty($historyData)) {
                StatusPengajuanHistories::insert($historyData);
            }

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil diubah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengajuan')->with('error', 'Pengajuan gagal diubah: ' . $e->getMessage());
        }
    }

    public function batalkanPengajuan(Request $request)
    {
        DB::beginTransaction();

        try {
            $kodePengajuan = $request->kode_pengajuan;

            // Ambil semua pengajuan dengan kode yang sama
            $pengajuans = Pengajuan::where('kode_pengajuan', $kodePengajuan)->get();

            if ($pengajuans->isEmpty()) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan.');
            }

            // Update status pengajuan menjadi "dibatalkan"
            Pengajuan::where('kode_pengajuan', $kodePengajuan)->update(['status' => 'dibatalkan']);

            // Simpan perubahan ke dalam `pengajuan_status_histories`
            $historyData = [];
            foreach ($pengajuans as $pengajuan) {
                $historyData[] = [
                    'kode_pengajuan' => $kodePengajuan,
                    'tanggal' => $pengajuan->tanggal,
                    'jam_mulai' => $pengajuan->jam_mulai,
                    'jam_selesai' => $pengajuan->jam_selesai,
                    'status' => 'dibatalkan',
                    'user_id' => $pengajuan->user_id,
                    'lab_id' => $pengajuan->lab_id,
                    'changed_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            StatusPengajuanHistories::insert($historyData);

            DB::commit();
            return redirect()->back()->with('success', "Pengajuan berhasil dibatalkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
