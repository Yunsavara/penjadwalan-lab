<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\ProsesPengajuan\TerimaProsesPengajuanRequest;
use App\Models\JadwalBooking;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProsesPengajuanController extends Controller
{
    public function index(){
        return view("laboran.booking-page.proses-pengajuan.proses-pengajuan", [
            'page_meta' => [
                'page' => 'Proses Pengajuan',
                'description' => 'Halaman untuk Proses Pengajuan Booking.'
            ]
        ]);
    }

    public function getDataProsesPengajuan(Request $request) {
        $query = PengajuanBooking::select([
            'id',
            'kode_booking',
            'status_pengajuan_booking',
            'keperluan_pengajuan_booking',
            'user_id',
            'lokasi_id'
        ])->where('lokasi_id', Auth::user()->lokasi_id);

        $totalData = $query->count();

        $filteredQuery = clone $query;

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $filteredQuery->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                    ->orWhere('status_pengajuan_booking', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $filteredQuery->count();

        
        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'kode_booking', 'id', 'keperluan_pengajuan_booking', 'status_pengajuan_booking', 'balasan_pengajuan_booking', 'user_id'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, $columns)) {
            $filteredQuery->orderBy($orderColumnName, $orderDirection);
        } else {
            $filteredQuery->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $filteredQuery->skip($start)->take($length)->get();

        // Format response
        $result = [];
        foreach ($data as $prosesPengajuan) {
            $result[] = [
                'id_pengajuan_booking' => Crypt::encryptString($prosesPengajuan->id),
                'kode_booking' => $prosesPengajuan->kode_booking,
                'status_pengajuan_booking' => $prosesPengajuan->status_pengajuan_booking,
                'keperluan_pengajuan_booking' => $prosesPengajuan->keperluan_pengajuan_booking,
                'user_id' => Crypt::encryptString($prosesPengajuan->user_id),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result,
        ]);
    }

    public function getDetailProsesPengajuan($id)
    {
        try {
            $id = Crypt::decryptString($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'ID tidak valid'], 400);
        }

        $pengajuanBooking = PengajuanBooking::with(['user', 'laboratorium', 'jadwalBookings']) 
            ->where('id', $id)
            ->where('lokasi_id', Auth::user()->lokasi_id) 
            ->first();

        if (!$pengajuanBooking) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'pengajuan_booking' => [
                'kode_booking' => $pengajuanBooking->kode_booking,
                'keperluan_pengajuan_booking' => $pengajuanBooking->keperluan_pengajuan_booking,
                'status_pengajuan_booking' => $pengajuanBooking->status_pengajuan_booking
            ],
            'user' => [
                'nama_pengguna' => $pengajuanBooking->user->nama_pengguna,
                'email_pengguna' => $pengajuanBooking->user->email,
                'nama_peran' => $pengajuanBooking->user->role->nama_peran,
                'prioritas' => $pengajuanBooking->user->role->prioritas_peran
            ],
            'laboratorium_unpam' => $pengajuanBooking->laboratorium->map(function ($lab) {
                return [
                    'id' => $lab->id,
                    'nama_laboratorium' => $lab->nama_laboratorium
                ];
            }),
            'jadwal_booking' => $pengajuanBooking->jadwalBookings->map(function ($jadwal) {
                return [
                    'tanggal_jadwal' => $jadwal->tanggal_jadwal,
                    'jam_mulai' => Carbon::parse($jadwal->jam_mulai)->format('H:i'),
                    'jam_selesai' => Carbon::parse($jadwal->jam_selesai)->format('H:i')
                ];
            })
        ]);
    }

    public function terimaProsesPengajuan($id, TerimaProsesPengajuanRequest $request)
    {
        DB::beginTransaction();

        try {
            // Dekripsi ID dan ambil pengajuan beserta jadwal dan user->role
            $pengajuan = PengajuanBooking::with(['jadwalBookings', 'user.role'])->findOrFail(Crypt::decryptString($id));

            if ($pengajuan->status_pengajuan_booking !== 'menunggu') {
                return redirect()->route('laboran.proses-pengajuan')->with('error', 'Pengajuan sudah diproses sebelumnya.');
            }

            // Setujui pengajuan utama
            $pengajuan->update([
                'status_pengajuan_booking' => 'diterima'
            ]);

            // Setujui semua jadwalnya
            $pengajuan->jadwalBookings()->update([
                'status' => 'diterima',
            ]);

            // Ambil prioritas peran user pengaju
            $prioritasPengajuan = $pengajuan->user->role->prioritas_peran;

            // Jika mode terima otomatis, cek dan tolak jadwal bentrok dengan prioritas lebih rendah
            if ($request->mode_terima === 'otomatis') {
                foreach ($pengajuan->jadwalBookings as $jadwal) {
                    $conflicts = JadwalBooking::where('tanggal_jadwal', $jadwal->tanggal_jadwal)
                        ->where('laboratorium_unpam_id', $jadwal->laboratorium_unpam_id)
                        ->where('status', 'diterima')
                        ->where(function ($query) use ($jadwal) {
                            $query->whereBetween('jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                                ->orWhereBetween('jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                                ->orWhere(function ($q) use ($jadwal) {
                                    $q->where('jam_mulai', '<=', $jadwal->jam_mulai)
                                    ->where('jam_selesai', '>=', $jadwal->jam_selesai);
                                });
                        })
                        ->whereHas('pengajuanBooking', function ($q) use ($prioritasPengajuan) {
                            $q->whereIn('status_pengajuan_booking', ['diterima', 'menunggu'])
                            ->whereHas('user.role', function ($q2) use ($prioritasPengajuan) {
                                $q2->where('prioritas_peran', '>', $prioritasPengajuan); // $prioritasPengajuan adalah yang ditolak
                            });
                        })
                        ->with('pengajuanBooking')
                        ->get();

                    foreach ($conflicts as $conflict) {
                        // Update status jadwal yang bentrok jadi ditolak
                        $conflict->update(['status' => 'ditolak']);

                        // Update status pengajuan induk jadwal tersebut jadi ditolak dengan alasan
                        $conflict->pengajuanBooking->update([
                            'status_pengajuan_booking' => 'ditolak',
                            'balasan_pengajuan_booking' => $request->alasan_penolakan_otomatis ?? 'Ditolak otomatis karena konflik dengan pengajuan prioritas lebih tinggi',
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('laboran.proses-pengajuan')->with('success', 'Pengajuan Berhasil Diterima.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.proses-pengajuan')->with('error', 'Pengajuan Tidak Berhasil Diterima.');
        }
    }
}