<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Semester;

class PenjadwalanController extends Controller
{
    public function index(){
        return view('laboran.penjadwalan-prodi.penjadwalan-prodi', [
            'page_meta' => [
                'page' => 'Generate Jadwal Prodi'
            ]
        ]);
    }

    // Note
    // Hapus Status Selesai Pada Semester

    public function create(){
        return view("laboran.penjadwalan.form-penjadawalan", [
            'Penjadawalan' => new Penjadwalan(),
            'page_meta' => [
                'page' => "Tambah Penjadwalan",
                'method' => 'POST',
                'url' => route('laboran.penjadwalan.create'),
                'button_text' => 'Tambah Penjadwalan'
            ]
        ]);
    }
}
