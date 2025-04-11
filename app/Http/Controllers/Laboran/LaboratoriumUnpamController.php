<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Laboran\LaboratoriumUnpam\LaboratoriumUnpamStoreRequest;
use App\Http\Requests\Laboran\LaboratoriumUnpam\LaboratoriumUnpamUpdateRequest;
use App\Models\Lokasi;

class LaboratoriumUnpamController extends Controller
{
    public function index(){
        $Jenislab = Jenislab::select(['id', 'name'])->get();
        $Lokasi = Lokasi::select(['id', 'name'])->get();

        return view("laboran.laboratorium-page.laboratorium", [
            'Laboratorium' => new LaboratoriumUnpam(),
            'Jenislab' => $Jenislab,
            'Lokasi' => $Lokasi,
            'page_meta' => [
                'page' => 'Laboratorium',
                'description' => 'Halaman untuk manajemen laboratorium dan jenis laboratorium.'
            ]
        ]);
    }

    public function store(LaboratoriumUnpamStoreRequest $Request){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            LaboratoriumUnpam::create($Request->all());

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal ditambahkan');
        }
    }

    public function getApiLaboratorium(Request $request) {
        $query = LaboratoriumUnpam::select(['name', 'slug', 'kapasitas', 'status', 'lokasi_id', 'jenislab_id']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('kapasitas', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $totalData = LaboratoriumUnpam::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'asc';

        $columns = ['index', 'name', 'kapasitas', 'status'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'name';

        // Hanya izinkan kolom DB untuk di-sort
        if (in_array($orderColumnName, ['name', 'kapasitas', 'status'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            // Kolom tidak valid, bisa fallback atau diabaikan
            $query->orderBy('name', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $laboratorium) {
            $result[] = [
                'index' => $start + $index + 1,
                'name' => $laboratorium->name,
                'slug' => $laboratorium->slug,
                'kapasitas' => $laboratorium->kapasitas,
                'status' => $laboratorium->status,
                'jenislab_id' => $laboratorium->jenislab->id,
                'lokasi_id' => $laboratorium->lokasi->id,
                'jenislab_name' => $laboratorium->jenislab->name,
                'lokasi_name' => $laboratorium->lokasi->name,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function update(LaboratoriumUnpamUpdateRequest $Request, LaboratoriumUnpam $Laboratorium){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $Laboratorium->update($Request->all());

            DB::commit();

            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal di-ubah');
        }
    }

    public function softDelete($slug)
    {
        DB::beginTransaction();

        try {
            $lab = LaboratoriumUnpam::where('slug', $slug)->firstOrFail();
            $lab->delete(); // ini akan soft delete

            DB::commit();
            return redirect()->route('laboran.laboratorium')->with('success', 'Laboratorium Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.laboratorium')->with('error', 'Laboratorium Gagal di-hapus');
        }
    }
}
