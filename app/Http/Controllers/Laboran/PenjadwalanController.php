<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    public function index(){
        return view('laboran.penjadwalan-prodi.penjadwalan-prodi', [
            'page_meta' => [
                'page' => 'Generate Jadwal Prodi'
            ]
        ]);
    }
}
