<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\MataKuliah;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MataKuliah\MataKuliahStoreRequest;
use App\Http\Requests\Admin\MataKuliah\MataKuliahUpdateRequest;

class MataKuliahController extends Controller
{
    public function index(){
        return view('admin.mata-kuliah.mata-kuliah', [
            'page_meta' => [
                'page' => 'Mata Kuliah'
            ]
        ]);
    }

    public function create(){

        // Buat foreach ke nama dosen
        $Dosen = User::whereHas('role', function ($query) {
                    $query->where('name', 'prodi');
                })->get();


        return view("admin.mata-kuliah.form-mata-kuliah", [
            'Dosen' => $Dosen,
            'MataKuliah' => new MataKuliah(),
            'page_meta' => [
                'page' => "Tambah Mata Kuliah",
                'method' => 'POST',
                'url' => route('admin.matakuliah.create'),
                'button_text' => 'Tambah Mata Kuliah'
            ]
        ]);
    }

    public function store(MataKuliahStoreRequest $Request){
        //dd($Request->all());

        DB::beginTransaction();

        try {

            $mataKuliah = MataKuliah::create([
                'name' => $Request->name,
            ]);

            // Hubungkan dengan dosen yang dipilih
            $mataKuliah->users()->sync($Request->dosen);

            DB::commit();

            return redirect()->route('admin.matakuliah')->with('success', 'Mata Kuliah Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.matakuliah.create')->with('error', 'Mata Kuliah Gagal ditambahkan');
        }

    }

    public function getData(Request $request)
    {
        // Menyaring data berdasarkan pencarian
        $query = MataKuliah::with('users:id,name') // Load relasi users hanya dengan id dan name
            ->select(['id', 'name', 'slug']);

        if ($search = $request->input('search.value')) {
            $query->where('name', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = MataKuliah::count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->map(function ($mataKuliah) {
                return [
                    'id' => $mataKuliah->id,
                    'name' => $mataKuliah->name,
                    'semester_id' => $mataKuliah->semester_id,
                    'dosen' => $mataKuliah->users->pluck('name')->implode(', '), // Menggabungkan nama dosen
                    'slug' => Str::slug($mataKuliah->name),
                ];
            }),
        ]);
    }

    public function edit(MataKuliah $MataKuliah){

        // Buat foreach ke nama dosen
        $Dosen = User::whereHas('role', function ($query) {
            $query->where('name', 'prodi');
        })->get();

        return view("admin.mata-kuliah.form-mata-kuliah", [
            'Dosen' => $Dosen,
            'MataKuliah' => $MataKuliah,
            'page_meta' => [
                'page' => "Ubah Mata Kuliah",
                'method' => 'PUT',
                'url' => route('admin.matakuliah.edit', $MataKuliah),
                'button_text' => 'Ubah Mata Kuliah'
            ]
        ]);
    }

    public function update(MataKuliahStoreRequest $request, MataKuliah $MataKuliah)
    {
        DB::beginTransaction();

        try {
            // Update Nama Mata Kuliah
            $MataKuliah->update([
                'name' => $request->name,
            ]);

            // Update daftar dosen yang terhubung
            $MataKuliah->users()->sync($request->dosen);

            DB::commit();

            return redirect()->route('admin.matakuliah')->with('success', 'Mata Kuliah Berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.matakuliah.edit')->with('error', 'Mata Kuliah Gagal diperbarui');
        }
    }

}
