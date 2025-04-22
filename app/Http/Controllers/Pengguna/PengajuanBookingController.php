<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanBookingController extends Controller
{
    public function index(){
        return view("pengguna.pengajuan-page.pengajuan-booking", [
            'page_meta' => [
                'page' => 'Pengajuan',
                'description' => 'Halaman untuk Membuat Pengajuan Booking.'
            ]
        ]);
    }
}
