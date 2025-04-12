<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\JenisLab\JenisLabStoreRequest;
use App\Http\Requests\Laboran\JenisLab\JenisLabUpdateRequest;

class JenisLabController extends Controller
{

    // Index Nya ada di LaboratoriumUnpamController, karena pakai modal untuk formnya jadi gk pindah halaman

    public function getApiJenisLaboratorium(Request $request) {
        $query = Jenislab::select(['name', 'slug', 'description']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $totalData = Jenislab::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'asc';

        $columns = ['index', 'name', 'slug', 'description'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'name';

        if (in_array($orderColumnName, ['name', 'slug', 'description'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('name', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $jenislab) {
            $result[] = [
                'index' => $start + $index + 1,
                'name' => $jenislab->name,
                'slug' => $jenislab->slug,
                'description' => $jenislab->description,
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

            Jenislab::create($Request->all());

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Laboratorium Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Laboratorium Gagal ditambahkan');
        }
    }

    public function update(JenisLabUpdateRequest $Request, Jenislab $Jenislab){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $Jenislab->update($Request->all());

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Jenis Lab Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Jenis Lab Gagal di-ubah');
        }
    }
}
