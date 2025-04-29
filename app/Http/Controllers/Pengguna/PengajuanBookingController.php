<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengguna\PengajuanBooking\PengajuanBookingStoreRequest;
use App\Models\HariOperasional;
use App\Models\JadwalBooking;
use App\Models\JamOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $Lokasi = Lokasi::select([
            'id',
            'nama_lokasi'
        ])->whereNot('nama_lokasi', 'fleksible')->get();

        return view("pengguna.pengajuan-page.form-pengajuan-booking", [
            'Lokasi' => $Lokasi,
            'page_meta' => [
                'page' => 'Buat Pengajuan Booking',
                'route_name' => 'pengajuan.store',
                'method' => 'POST',
                'description' => 'Halaman untuk input Data Pengajuan Booking'
            ] 
        ]);
    }

    public function getLaboratoriumByLokasi($lokasi_id)
    {
        $laboratorium = LaboratoriumUnpam::where('lokasi_id', $lokasi_id)
                        ->where('status_laboratorium', 'tersedia')
                        ->select('id', 'nama_laboratorium')
                        ->get();

        return response()->json($laboratorium);
    }

    public function getHariOperasionalByLokasi($lokasi_id)
    {
        $hariOperasional =   HariOperasional::select([
                                'id',
                                'hari_operasional',
                                'lokasi_id',
                                'is_disabled'
                            ])
                            ->where('lokasi_id', $lokasi_id)
                            ->where('is_disabled', false)
                            ->get();
        
        return response()->json($hariOperasional);
    }

    public function getJamOperasional($hariOperasionalId)
    {
        // Ambil data jam operasional berdasarkan hari operasional
        $jamOperasional = JamOperasional::where('hari_operasional_id', $hariOperasionalId)
                                         ->where('is_disabled', false)
                                         ->get(['jam_mulai', 'jam_selesai']);

        return response()->json($jamOperasional);
    }

    public function store(PengajuanBookingStoreRequest $Request)
    {
        dd($Request->validated());
    }

}
