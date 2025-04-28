<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembatalanJadwal extends Model
{
    protected $fillable = [
        'jadwal_booking_id',
        'alasan_pembatalan_jadwal',
        'balasan_pembatalan_jadwal',
        'status_pembatalan_jadwal',
        'user_id',
    ];

    public function jadwalBooking()
    {
        return $this->belongsTo(JadwalBooking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
