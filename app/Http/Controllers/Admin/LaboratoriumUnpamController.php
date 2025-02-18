<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jenislab;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaboratoriumUnpam\LaboratoriumUnpamStoreRequest;

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

}
