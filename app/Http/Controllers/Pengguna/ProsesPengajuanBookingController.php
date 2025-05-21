<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProsesPengajuanBookingController extends Controller
{
    public function index()
    {
        return view("laboran.proses-pengajuan-booking.proses-pengajuan-booking", [
            'page_meta' => [
                'page' => 'Proses Pengajuan Booking',
                'description' => 'Halaman Proses Pengajuan Booking.'
            ]
        ]);
    }
}
