<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Lokasi;
use App\Models\Jenislab;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Laboran\LaboratoriumUnpam\LaboratoriumUnpamStoreRequest;
use App\Http\Requests\Laboran\LaboratoriumUnpam\LaboratoriumUnpamUpdateRequest;

class LaboratoriumUnpamController extends Controller
{

    public function index(){
        $Jenislab = Jenislab::select(['id', 'nama_jenis_lab'])->get();
        $Lokasi = Lokasi::select(['id', 'nama_lokasi'])->whereNot('nama_lokasi', 'fleksible')->get();

        return view("laboran.laboratorium-page.laboratorium", [
            'Laboratorium' => new LaboratoriumUnpam(),
            'JenisLaboratorium' => new Jenislab(),
            'Jenislab' => $Jenislab,
            'Lokasi' => $Lokasi,
            'page_meta' => [
                'page' => 'Laboratorium',
                'description' => 'Halaman untuk manajemen laboratorium dan jenis laboratorium.'
            ]
        ]);
    }

    public function getApiLaboratorium(Request $request) {
        $query = LaboratoriumUnpam::select(['id', 'nama_laboratorium', 'kapasitas_laboratorium', 'status_laboratorium', 'lokasi_id', 'jenislab_id', 'deskripsi_laboratorium']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_laboratorium', 'like', "%{$search}%")
                    ->orWhere('kapasitas_laboratorium', 'like', "%{$search}%")
                    ->orWhere('status_laboratorium', 'like', "%{$search}%");
            });
        }

        $totalData = LaboratoriumUnpam::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null,'nama_laboratorium', 'id', 'kapasitas_laboratorium', 'status_laboratorium', 'deskripsi_laboratorium'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        // Hanya izinkan kolom DB untuk di-sort
        if (in_array($orderColumnName, ['id', 'nama_laboratorium', 'kapasitas_laboratorium', 'status_laboratorium', 'deskripsi_laboratorium'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            // Kolom tidak valid, bisa fallback atau diabaikan
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $laboratorium) {
            $result[] = [
                'id_laboratorium' => Crypt::encryptString($laboratorium->id),
                'nama_laboratorium' => $laboratorium->nama_laboratorium,
                'kapasitas_laboratorium' => $laboratorium->kapasitas_laboratorium,
                'status_laboratorium' => $laboratorium->status_laboratorium,
                'jenislab_id' => $laboratorium->jenislab->id,
                'lokasi_id' => $laboratorium->lokasi->id,
                'deskripsi_laboratorium' => $laboratorium->deskripsi_laboratorium,
                'nama_jenislab' => $laboratorium->jenislab->nama_jenis_lab,
                'nama_lokasi' => $laboratorium->lokasi->nama_lokasi,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function store(LaboratoriumUnpamStoreRequest $Request){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            $data = $Request->validated();

            LaboratoriumUnpam::create([
                'nama_laboratorium' => $data['nama_laboratorium_store'],
                'kapasitas_laboratorium' => $data['kapasitas_laboratorium_store'],
                'status_laboratorium' => $data['status_laboratorium_store'],
                'lokasi_id' => $data['lokasi_id_store'],
                'jenislab_id' => $data['jenislab_id_store'],
                'deskripsi_laboratorium' => $data['deskripsi_laboratorium_store']
            ]);

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal ditambahkan <br>' . $e->getMessage());
        }
    }

    public function update(LaboratoriumUnpamUpdateRequest $Request, $id){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            $data = $Request->validated();

            $Laboratorium = LaboratoriumUnpam::findOrFail(Crypt::decryptString($id));

            $Laboratorium->update([
                'nama_laboratorium' => $data['nama_laboratorium_update'],
                'jenislab_id' => $data['jenislab_id_update'],
                'lokasi_id' => $data['lokasi_id_update'],
                'kapasitas_laboratorium' => $data['kapasitas_laboratorium_update'],
                'status_laboratorium' => $data['status_laboratorium_update'],
                'deskripsi_laboratorium' => $data['deskripsi_laboratorium_update']
            ]);

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal di-ubah');
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();

        try {
            $lab = LaboratoriumUnpam::where('id', Crypt::decryptString($id))->firstOrFail();
            $lab->delete(); // ini akan soft delete

            DB::commit();
            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal di-hapus');
        }
    }
}
