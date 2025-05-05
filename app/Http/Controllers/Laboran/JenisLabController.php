<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Laboran\JenisLab\JenisLabStoreRequest;
use App\Http\Requests\Laboran\JenisLab\JenisLabUpdateRequest;

class JenisLabController extends Controller
{

    // Index Nya ada di LaboratoriumUnpamController, karena pakai modal untuk formnya jadi gk pindah halaman

    public function getApiJenisLaboratorium(Request $request) {
        $query = Jenislab::select(['id','nama_jenis_lab', 'deskripsi_jenis_lab']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_jenis_lab', 'like', "%{$search}%")
                    ->orWhere('deskripsi_jenis_lab', 'like', "%{$search}%");
            });
        }

        $totalData = Jenislab::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'nama_jenis_lab','id', 'deskripsi_jenis_lab'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, ['id','nama_jenis_lab', 'deskripsi_jenis_lab'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination (limit data yang tampil per page)
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $jenislab) {
            $result[] = [
                // 'id' => $jenislab->id,
                'id_jenis_lab' => Crypt::encryptString($jenislab->id),
                'nama_jenis_lab' => $jenislab->nama_jenis_lab,
                'deskripsi_jenis_lab' => $jenislab->deskripsi_jenis_lab,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function store(JenisLabStoreRequest $Request){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            $data = $Request->validated();

            Jenislab::create([
                'nama_jenis_lab' => $data['nama_jenis_lab_store'],
                'deskripsi_jenis_lab' => $data['deskripsi_jenis_lab_store']
            ]);

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Laboratorium Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Laboratorium Gagal ditambahkan <br>'. $e->getMessage());
        }
    }

    public function update(JenisLabUpdateRequest $Request, $id){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $data = $Request->validated();

            $Jenislab = Jenislab::findOrFail(Crypt::decryptString($id));

            $Jenislab->update([
                'nama_jenis_lab' => $data['nama_jenis_lab_update'],
                'deskripsi_jenis_lab' => $data['deskripsi_jenis_lab_update']
            ]);

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Lab Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Lab Gagal di-ubah');
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();

        try {
            $lab = Jenislab::findOrFail(Crypt::decryptString($id));
            $lab->delete(); // ini akan soft delete

            DB::commit();
            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Lab Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Lab Gagal di-hapus');
        }
    }
}
