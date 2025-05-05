<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalBookingController extends Controller
{
    public function index(){
        return view("pengguna.jadwal-page.jadwal", [
            'page_meta' => [
                'page' => 'Jadwal',
                'description' => 'Halaman Jadwal Yang Sudah Terdaftar.'
            ]
        ]);
    }
}
