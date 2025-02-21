<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index(){
        return view('laboran.booking.booking', [
            'page_meta' => [
                'page' => 'Booking'
            ]
        ]);
    }

    public function create(){
        return view("laboran.booking.form-booking", [
            'Jadwal' => new Jadwal(),
            'page_meta' => [
                'page' => "Tambah Booking",
                'method' => 'POST',
                'url' => route('laboran.booking.create'),
                'button_text' => 'Tambah Booking'
            ]
        ]);
    }
}
