<?php

namespace App\Http\Controllers\AllRole;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index(){
        return view('all-role.pengajuan', [
            'page_meta' => [
                'page' => 'Pengajuan'
            ]
        ]);
    }

    public function create(){
        return view("all-role.form-pengajuan", [
            'Jadwal' => new Jadwal(),
            'page_meta' => [
                'page' => "Tambah Pengajuan",
                'method' => 'POST',
                'url' => route('pengajuan.create'),
                'button_text' => 'Tambah Pengajuan'
            ]
        ]);
    }
}
