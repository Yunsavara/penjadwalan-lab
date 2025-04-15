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
        $query = Jenislab::select(['id','name_jenis_lab', 'description_jenis_lab']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name_jenis_lab', 'like', "%{$search}%")
                    ->orWhere('description_jenis_lab', 'like', "%{$search}%");
            });
        }

        $totalData = Jenislab::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'asc';

        $columns = ['index', 'id', 'name_jenis_lab', 'description_jenis_lab'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'name_jenis_lab';

        if (in_array($orderColumnName, ['name_jenis_lab', 'description_jenis_lab'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('name_jenis_lab', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $jenislab) {
            $result[] = [
                'index' => $start + $index + 1,
                // 'id' => $jenislab->id,
                'id_jenis_lab' => Crypt::encryptString($jenislab->id),
                'name_jenis_lab' => $jenislab->name_jenis_lab,
                'slug_jenis_lab' => $jenislab->slug_jenis_lab,
                'description_jenis_lab' => $jenislab->description_jenis_lab,
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
                'name_jenis_lab' => $data['name_jenis_lab_store'],
                'description_jenis_lab' => $data['description_jenis_lab_store']
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
                'name_jenis_lab' => $data['name_jenis_lab_update'],
                'description_jenis_lab' => $data['description_jenis_lab_update']
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
            $lab = Jenislab::where('id', Crypt::decryptString($id))->firstOrFail();
            $lab->delete(); // ini akan soft delete

            DB::commit();
            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Lab Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Lab Gagal di-hapus');
        }
    }
}
