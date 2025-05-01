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

        $lokasi = Lokasi::select([
            'id',
            'nama_lokasi'
        ])->whereNot('nama_lokasi', 'fleksible')->get();

        return view("pengguna.pengajuan-page.form-pengajuan-booking-store", [
            'old_sesi' => session('old_sesi', []),
            'old_sesi_tanggal' => session('old_sesi_tanggal', []),
            'old_hari' => session('old_hari', []),
            'old_labs' => session('old_labs', []),
            'old_range' => session('old_range', []),
            'lokasi' => $lokasi,
            'page_meta' => [
                'page' => 'Buat Pengajuan Booking',
                'route_name' => 'pengajuan.store',
                'method' => 'POST',
                'description' => 'Halaman untuk input Data Pengajuan Booking'
            ] 
        ]);
    }

    public function getLaboratoriumByLokasi($lokasiId)
    {
        $laboratorium = LaboratoriumUnpam::where('lokasi_id', $lokasiId)
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
        $jamOperasional = JamOperasional::where('hari_operasional_id', $hariOperasionalId)
                                        ->where('is_disabled', false)
                                        ->get(['jam_mulai', 'jam_selesai'])
                                        ->map(function ($item) {
                                            return [
                                                'jam_mulai' => date('H:i', strtotime($item->jam_mulai)),
                                                'jam_selesai' => date('H:i', strtotime($item->jam_selesai)),
                                            ];
                                        });

        return response()->json($jamOperasional);
    }


    public function toPivotData(): array
    {
        $pivotData = [];

        foreach ($this->laboratorium as $labId) {
            foreach ($this->sesi_tanggal as $tanggal => $jamList) {
                foreach ($jamList as $jam) {
                    [$mulai, $selesai] = explode(' - ', $jam);
                    $pivotData[] = [
                        'laboratorium_id' => $labId,
                        'tanggal' => $tanggal,
                        'jam_mulai' => $mulai,
                        'jam_selesai' => $selesai,
                    ];
                }
            }
        }

        return $pivotData;
    }


    public function store(PengajuanBookingStoreRequest $Request)
    {   
        $pivotData = $Request->toPivotData();

        dd($pivotData);
    }

}
