<?php

namespace App\Http\Controllers\AllRole;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Booking;
use App\Models\Pengajuan;
use App\Models\BookingLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BookingDetail;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StatusPengajuanHistories;
use App\Http\Requests\AllRole\PengajuanStoreRequest;
use App\Http\Requests\AllRole\PengajuanUpdateRequest;

class PengajuanController extends Controller
{
    public function index(){
        Log::info("Session error di index():", ['session' => session('error')]);
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
        $kode_pengajuan = 'PJ-' . Carbon::now()->format('YmdHi') . Str::upper(Str::random(3));

        DB::beginTransaction();

        try {
            $user_id = auth()->id();
            $user_priority = auth()->user()->role->priority; // Ambil priority user
            $pesan_bentrok = [];

            // 1. Cek apakah user sudah memiliki pengajuan untuk lab & tanggal yang sama
            foreach ($request->lab_id as $lab) {
                foreach ($request->tanggal_pengajuan as $tgl) {
                    $bentrok_diri = BookingDetail::where('lab_id', $lab)
                        ->where('tanggal', $tgl)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->whereHas('booking', function ($query) use ($user_id) {
                            $query->where('user_id', $user_id);
                        })
                        ->with(['booking', 'laboratorium'])
                        ->get();

                    if ($bentrok_diri->isNotEmpty()) {
                        foreach ($bentrok_diri as $data) {
                            $pesan_bentrok[] = "Kode Pengajuan: <strong>{$data->booking->kode_pengajuan}</strong><br> Lab {$data->laboratorium->name} tanggal {$data->tanggal} dari {$data->jam_mulai} sampai {$data->jam_selesai} (Status: {$data->status})";
                        }
                    }
                }
            }

            // Jika ada bentrok dengan diri sendiri, langsung tolak
            if (!empty($pesan_bentrok)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Pengajuan bentrok dengan jadwal berikut:<br>' . implode('<br>', $pesan_bentrok));
            }

            // 2. Cek apakah ada bentrok dengan orang lain yang memiliki priority lebih tinggi
            foreach ($request->lab_id as $lab) {
                foreach ($request->tanggal_pengajuan as $tgl) {
                    $bentrok_orang_lain = BookingDetail::where('lab_id', $lab)
                        ->where('tanggal', $tgl)
                        ->whereIn('status', ['diterima', 'digunakan']) // Hanya cek yang sudah diterima/digunakan
                        ->whereHas('booking.user', function ($query) use ($user_id) {
                            $query->where('id', '!=', $user_id); // Jangan cek diri sendiri
                        })
                        ->with(['booking.user', 'laboratorium']) // Ambil data user untuk priority
                        ->get();

                    foreach ($bentrok_orang_lain as $data) {
                        $priority_orang_lain = $data->booking->user->role->priority;

                        if ($user_priority < $priority_orang_lain) {
                            // Jika priority user lebih tinggi, pengajuan tetap masuk tanpa mengganti status booking lain
                            continue;
                        } else {
                            // Jika priority user lebih rendah, tidak bisa booking
                            $pesan_bentrok[] = "Lab {$data->laboratorium->name} pada tanggal {$data->tanggal} dari {$data->jam_mulai} sampai {$data->jam_selesai}";
                        }
                    }
                }
            }

            // Jika ada bentrok dengan orang lain yang lebih tinggi priority-nya, tolak
            if (!empty($pesan_bentrok)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Pengajuan tidak dapat diproses karena ada jadwal dengan prioritas lebih tinggi:<br>' . implode('<br>', $pesan_bentrok));
            }

            // 3. Simpan booking jika semua check lolos
            $booking = Booking::create([
                'kode_pengajuan' => $kode_pengajuan,
                'user_id' => $user_id,
                'status' => 'pending',
            ]);

            foreach ($request->lab_id as $lab) {
                foreach ($request->tanggal_pengajuan as $tgl) {
                    $bookingDetail = BookingDetail::create([
                        'kode_pengajuan' => $kode_pengajuan,
                        'lab_id' => $lab,
                        'tanggal' => $tgl,
                        'jam_mulai' => $request->jam_mulai,
                        'jam_selesai' => $request->jam_selesai,
                        'status' => 'pending',
                        'keperluan' => $request->keperluan,
                    ]);

                    // Masukin ke log
                    BookingLog::create([
                        'booking_detail_id' => $bookingDetail->id,
                        'user_id' => $user_id,
                        'status' => 'pending',
                        'catatan' => 'Pengajuan diajukan',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil diajukan dengan kode ' . $kode_pengajuan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Datatables jadwal
    public function getDataJadwal(Request $request)
    {
        $userId = Auth::id();

        $query = Jadwal::select('kode_pengajuan', 'keperluan', 'status', 'lab_id')
            ->where('user_id', $userId); // Filter berdasarkan user yang login

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%$search%")
                ->orWhere('keperluan', 'like', "%$search%");
            });
        }

        // Clone query untuk menghitung total record setelah filtering
        $recordsFiltered = $query->count();

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination (tanpa filter), tetap hanya untuk user login
        $recordsTotal = Jadwal::where('user_id', $userId)->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    // Datatables
    public function getDataBooking(Request $request)
    {
        $user_id = auth()->id();

        // Ambil data dari tabel `bookings` dengan relasi ke `booking_details`
        $query = Booking::with(['bookingDetail.laboratorium'])
            ->where('user_id', $user_id); // Tetap filter berdasarkan user login

        // Pencarian (jika ada)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                ->orWhereHas('bookingDetail', function ($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%");
                })
                ->orWhereHas('bookingDetail.laboratorium', function ($q) use ($search) {
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
        foreach ($data as $index => $booking) {
            // Gabungkan semua lab dalam satu string
            $labs = $booking->bookingDetail->pluck('laboratorium.name')->unique()->implode(', ');

            // Gabungkan semua status dalam satu string (jika perlu)
            $statuses = $booking->bookingDetail->pluck('status')->unique()->implode(', ');

            $result[] = [
                'index' => $start + $index + 1,
                'kode_pengajuan' => $booking->kode_pengajuan,
                'lab' => $labs,
                'status' => ucfirst($statuses),
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
    public function getDetailBooking($kode_pengajuan)
    {
        // Ambil booking berdasarkan kode_pengajuan
        $booking = Booking::with(['bookingDetail.laboratorium'])
            ->where('kode_pengajuan', $kode_pengajuan)
            ->first();

        // Jika tidak ditemukan
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        // Ambil detail booking
        $details = $booking->bookingDetail->map(function ($detail) {
            return [
                'lab' => $detail->laboratorium->name,
                'tanggal' => $detail->tanggal,
                'jam_mulai' => $detail->jam_mulai,
                'jam_selesai' => $detail->jam_selesai,
                'status' => ucfirst($detail->status),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'kode_pengajuan' => $booking->kode_pengajuan,
                'status' => ucfirst($booking->status),
                'details' => $details,
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

    public function batalkanJadwal(Request $request)
    {
        DB::beginTransaction();

        try {
            $kodePengajuan = $request->kode_pengajuan;

            // Ambil semua pengajuan dengan kode yang sama
            $jadwals = Jadwal::where('kode_pengajuan', $kodePengajuan)->get();

            if ($jadwals->isEmpty()) {
                return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
            }

            // Update status pengajuan menjadi "dibatalkan"
            Jadwal::where('kode_pengajuan', $kodePengajuan)->update(['status' => 'dibatalkan']);

            // Simpan perubahan ke dalam `pengajuan_status_histories`
            $historyData = [];
            foreach ($jadwals as $jadwal) {
                $historyData[] = [
                    'kode_pengajuan' => $kodePengajuan,
                    'tanggal' => $jadwal->tanggal,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'status' => 'dibatalkan',
                    'user_id' => $jadwal->user_id,
                    'lab_id' => $jadwal->lab_id,
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
