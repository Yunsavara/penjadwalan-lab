<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'kode_pengajuan',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'alasan_booking',
        'balasan_booking',
        'status',
        'user_id',
        'laboratorium_unpam_id',
    ];

    
}
