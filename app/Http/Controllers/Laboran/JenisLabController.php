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
    public function index(){
        return view("laboran.jenis-lab.jenis-lab", [
            'page_meta' => [
                'page' => 'Jenis Lab'
            ]
        ]);
    }

    public function getData(Request $request)
    {
        // Menyaring data berdasarkan pencarian jika ada
        $query = Jenislab::query()->select(['id','name','slug','description']);

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
        return view("laboran.jenis-lab.form-jenis-lab", [
            'Jenislab' => new Jenislab(),
            'page_meta' => [
                'page' => "Tambah Jenis Lab",
                'method' => 'POST',
                'url' => route('laboran.jenis-lab.create'),
                'button_text' => 'Tambah Jenis Lab'
            ]
        ]);
    }

    public function store(JenisLabStoreRequest $Request){
        //dd($Request->all());

        DB::beginTransaction();
        try {

            Jenislab::create($Request->all());

            DB::commit();

            return redirect()->route('laboran.jenis-lab')->with('success', 'Jenis Lab Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.jenis-lab.create')->with('error', 'Jenis Lab Gagal ditambahkan');
        }
    }

    public function edit(Jenislab $Jenislab){
        return view("laboran.jenis-lab.form-jenis-lab", [
            'Jenislab' => $Jenislab,
            'page_meta' => [
                'page' => "Ubah Jenis Lab",
                'method' => 'PUT',
                'url' => route('laboran.jenis-lab.edit', $Jenislab),
                'button_text' => 'Ubah Jenis Lab'
            ]
        ]);
    }

    public function update(JenisLabUpdateRequest $Request, Jenislab $Jenislab){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $Jenislab->update($Request->all());

            DB::commit();

            return redirect()->route('laboran.jenis-lab')->with('success', 'Jenis Lab Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('laboran.jenis-lab.edit')->with('error', 'Jenis Lab Gagal di-ubah');
        }
    }
}
