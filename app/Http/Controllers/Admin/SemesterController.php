<?php

namespace App\Http\Controllers\Admin;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Semester\SemesterStoreRequest;
use App\Http\Requests\Admin\Semester\SemesterUpdateRequest;

class SemesterController extends Controller
{
    public function index(){
        return view('admin.semester.semester', [
            'page_meta' => [
                'page' => 'Semester'
            ]
        ]);
    }

    public function create(){
        return view("admin.semester.form-semester", [
            'Semester' => new Semester(),
            'page_meta' => [
                'page' => "Tambah Semester",
                'method' => 'POST',
                'url' => route('admin.semester.create'),
                'button_text' => 'Tambah Semester'
            ]
        ]);
    }

    public function store(SemesterStoreRequest $Request){
        // dd($Request->all());

        DB::beginTransaction();
        try {

            Semester::create($Request->all());

            DB::commit();

            return redirect()->route('admin.semester')->with('success', 'Semester Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.semester.create')->with('error', 'Semester Gagal ditambahkan');
        }
    }

    public function getData(Request $request)
    {
        // Menyaring data berdasarkan pencarian
        $query = Semester::query()->select(['id','name','slug','tanggal_mulai', 'tanggal_akhir', 'status']);

        if ($search = $request->input('search.value')) {
            $query->where('name', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = Semester::count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }

    public function edit(Semester $Semester){
        // Cek apakah semester memiliki status 'aktif' atau 'selesai'
        if (in_array($Semester->status, ['aktif', 'selesai'])) {
            return redirect()->route('admin.semester')
                            ->with('error', 'Semester tidak dapat diedit karena sudah aktif atau selesai.');
        }
        return view("admin.semester.form-semester", [
            'Semester' => $Semester,
            'page_meta' => [
                'page' => "Ubah Semester",
                'method' => 'PUT',
                'url' => route('admin.semester.edit', $Semester),
                'button_text' => 'Ubah Semester'
            ]
        ]);
    }

    public function update(SemesterUpdateRequest $Request, Semester $Semester){
        // dd($Request->all());

        DB::beginTransaction();
        try {
            $Semester->update($Request->all());

            DB::commit();

            return redirect()->route('admin.semester')->with('success', 'Semester Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.semester.edit')->with('error', 'Semester Gagal di-ubah');
        }
    }
}
