<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaboratoriumUnpam\LaboratoriumUnpamStoreRequest;
use App\Http\Requests\Admin\LaboratoriumUnpam\LaboratoriumUnpamUpdateRequest;

class LaboratoriumUnpamController extends Controller
{
    public function index(){
        return view("admin.laboratorium.laboratorium", [
            'page_meta' => [
                'page' => 'Laboratorium'
            ]
        ]);
    }

    public function create(){

        $Jenislab = Jenislab::select(['id', 'name'])->get();

        return view("admin.laboratorium.form-laboratorium", [
            'Laboratorium' => new LaboratoriumUnpam(),
            'Jenislab' => $Jenislab,
            'page_meta' => [
                'page' => "Tambah Laboratorium",
                'method' => 'POST',
                'url' => route('admin.laboratorium.create'),
                'button_text' => 'Tambah Laboratorium'
            ]
        ]);
    }

    public function store(LaboratoriumUnpamStoreRequest $Request){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            LaboratoriumUnpam::create($Request->all());

            DB::commit();

            return redirect()->route('admin.laboratorium')->with('success', 'Laboratorium Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.laboratorium.create')->with('error', 'Laboratorium Gagal ditambahkan');
        }
    }

    // Untuk Datatables
    public function getData(Request $request)
    {
        // Query dengan join ke tabel jenislabs
        $query = LaboratoriumUnpam::query()
            ->select([
                'laboratorium_unpams.id',
                'laboratorium_unpams.name',
                'laboratorium_unpams.slug as slug',
                'jenislabs.name as jenislab_name',
                'laboratorium_unpams.lokasi',
                'laboratorium_unpams.kapasitas',
                'laboratorium_unpams.status'
            ])
            ->leftJoin('jenislabs', 'laboratorium_unpams.jenislab_id', '=', 'jenislabs.id');

        // Filter berdasarkan pencarian
        if ($search = $request->input('search.value')) {
            $query->where('laboratorium_unpams.name', 'like', "%$search%")
                ->orWhere('laboratorium_unpams.lokasi', 'like', "%$search%")
                ->orWhere('jenislabs.name', 'like', "%$search%"); // Tambahkan filter nama jenislab
        }

        // Pagination
        $data = $query->paginate($request->input('length'));
        $recordsTotal = LaboratoriumUnpam::count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    public function edit(LaboratoriumUnpam $Laboratorium){
        $Jenislab = Jenislab::select(['id', 'name'])->get();
        return view("admin.laboratorium.form-laboratorium", [
            'Laboratorium' => $Laboratorium,
            'Jenislab' => $Jenislab,
            'page_meta' => [
                'page' => "Ubah Laboratorium",
                'method' => 'PUT',
                'url' => route('admin.laboratorium.edit', $Laboratorium),
                'button_text' => 'Ubah Laboratorium'
            ]
        ]);
    }

    public function update(LaboratoriumUnpamUpdateRequest $Request, LaboratoriumUnpam $Laboratorium){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $Laboratorium->update($Request->all());

            DB::commit();

            return redirect()->route('admin.laboratorium')->with('success', 'Laboratorium Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.laboratorium.edit')->with('error', 'Laboratorium Gagal di-ubah');
        }
    }
}
