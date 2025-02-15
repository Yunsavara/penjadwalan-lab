<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\JenislabDataTable;
use App\Models\Jenislab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JenisLab\JenisLabStoreRequest;

class JenislabController extends Controller
{
    public function index(JenislabDataTable $datatable){
        return $datatable->render("admin.jenis-lab.jenis-lab", [
            'page_meta' => [
                'page' => 'Jenis Lab'
            ]
        ]);
    }

//     // Ambil Data Lewat AJAX ke DataTables
// public function getDataJenisLab(Request $request)
// {
//     $query = JenisLab::select('id', 'name', 'description');

//     // Filtering manual untuk kolom name (jika diperlukan)
//     if ($request->has('search') && $request->search['value']) {
//         $keyword = $request->search['value'];
//         $query->where('name', 'like', "%$keyword%");
//     }

//     // Pagination: Ambil jumlah data sesuai request dari DataTables
//     $totalData = $query->count(); // Hitung total sebelum paginasi
//     $data = $query->offset($request->start)->limit($request->length)->get();

//     return DataTables::of($data)
//         ->setTotalRecords($totalData) // Pastikan jumlah total data dikirim
//         ->addIndexColumn()
//         ->make(true);
// }

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
