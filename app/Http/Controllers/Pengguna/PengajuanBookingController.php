<?php

namespace App\Http\Controllers\Pengguna;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JadwalBooking;
use App\Models\PengajuanBooking;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pengguna\PengajuanBooking\PengajuanBookingStoreRequest;

class PengajuanBookingController extends Controller
{
    public function index() {
        return view("pengguna.pengajuan-page.pengajuan-booking", [
            'page_meta' => [
                'page' => 'Pengajuan',
                'description' => 'Halaman untuk Membuat Pengajuan Booking.'
            ]
        ]);
    }

    public function create() {

        $Laboratoriums = LaboratoriumUnpam::select([
                        'id',
                        'nama_laboratorium',
                        'kapasitas_laboratorium',
                        'lokasi_id','jenislab_id'
                        ])->with(['lokasi','jenislab'])->get();

        return view("pengguna.pengajuan-page.form-pengajuan-booking", [
            'Laboratoriums' => $Laboratoriums,
            'page_meta' => [
                'page' => 'Buat Pengajuan Booking',
                'route_name' => 'pengajuan.store',
                'method' => 'POST',
                'description' => 'Halaman untuk input Data Pengajuan Booking'
            ]
        ]);
    }

    public function store(PengajuanBookingStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel pengajuan_bookings
            $pengajuan = PengajuanBooking::create([
                'kode_pengajuan' => 'PB-' . strtoupper(Str::random(6)),
                'status_pengajuan_booking' => 'menunggu',
                'keperluan_pengajuan_booking' => $request->keperluan_pengajuan_booking,
                'user_id' => auth()->id(),
            ]);

            // 2. Simpan ke tabel jadwal_bookings
            foreach ($request->booking as $tanggal => $labData) {
                foreach ($labData as $labId => $jamArray) {
                    foreach ($jamArray as $jam) {
                        $jamMulai = sprintf('%02d:00:00', $jam);
                        $jamSelesai = sprintf('%02d:59:00', $jam);

                        JadwalBooking::create([
                            'tanggal_jadwal' => $tanggal,
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'status_jadwal_booking' => 'menunggu',
                            'pengajuan_booking_id' => $pengajuan->id,
                            'laboratorium_unpam_id' => $labId,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('pengajuan')->with('success', 'Pengajuan booking berhasil dikirim!');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('pengajuan.create')->with('error', 'Pengajuan Booking Gagal diajukan! <br>'. $e->getMessage());
        }
    }

}
