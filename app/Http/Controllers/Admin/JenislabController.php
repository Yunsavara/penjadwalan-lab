<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JenisLab\JenisLabStoreRequest;

class JenislabController extends Controller
{
    public function index(){
        return view("admin.jenis-lab.jenis-lab", [
            'page_meta' => [
                'page' => 'Jenis Lab'
            ]
        ]);
    }

    public function getData(Request $request)
    {
        // Menyaring data berdasarkan pencarian jika ada
        $query = Jenislab::query();

        if ($search = $request->input('search.value')) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = Jenislab::count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    public function create(){
        return view("admin.jenis-lab.form-jenis-lab", [
            'jenisLab' => new Jenislab(),
            'page_meta' => [
                'page' => "Tambah Jenis Lab",
                'method' => 'POST',
                'url' => route('admin.jenis-lab.create'),
                'button_text' => 'Tambah Jenis Lab'
            ]
        ]);
    }

    public function store(JenisLabStoreRequest $Request){
        //dd($Request->all());

        DB::beginTransaction();
        try {
            $jenisLab = Jenislab::create($Request->all());

            DB::commit();

            return redirect()->route('admin.jenis-lab')->with('success', 'Jenis Lab Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.jenis-lab.create')->with('error', 'Jenis Lab Gagal ditambahkan');
        }
    }


}
