<?php

namespace App\Http\Controllers\AllRole;

use App\Models\Jadwal;
use App\Models\Pengajuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
                'status' => 'pending',
            ]);

            // Simpan data ke tabel `jadwals` untuk setiap tanggal
            foreach ($request->tanggal_pengajuan as $tanggal) {
                if (!isset($request->jam_mulai[$tanggal]) || !isset($request->jam_selesai[$tanggal])) {
                    continue; // Lewati jika jam mulai/selesai tidak ada untuk tanggal ini
                }

                Jadwal::create([
                    'kode_pengajuan' => $kodePengajuan,
                    'keperluan' => $request->keperluan,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $request->jam_mulai[$tanggal],
                    'jam_selesai' => $request->jam_selesai[$tanggal],
                    'status' => 'belum dipakai',
                    'lab_id' => $request->lab_id,
                    'user_id' => $request->user_id,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan berhasil diajukan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // public function edit(LaboratoriumUnpam $Laboratorium){
    //     $Jenislab = Jenislab::select(['id', 'name'])->get();
    //     return view("laboran.laboratorium.form-laboratorium", [
    //         'Laboratorium' => $Laboratorium,
    //         'Jenislab' => $Jenislab,
    //         'page_meta' => [
    //             'page' => "Ubah Laboratorium",
    //             'method' => 'PUT',
    //             'url' => route('laboran.laboratorium.edit', $Laboratorium),
    //             'button_text' => 'Ubah Laboratorium'
    //         ]
    //     ]);
    // }
}
