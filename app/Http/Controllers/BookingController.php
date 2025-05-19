<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return view('pengguna.booking.booking', [
            'page_meta' => [
                'page' => 'Halaman Booking',
                'description' => 'Halaman untuk pengajuan dan jadwal booking.'
            ]
        ]);
    }
}
