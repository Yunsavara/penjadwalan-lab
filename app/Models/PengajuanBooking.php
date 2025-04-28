<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBooking extends Model
{
    protected $fillable = [
        'kode_booking',
        'status_pengajuan_booking',
        'keperluan_pengajuan_booking',
        'balasan_pengajuan_booking',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalBookings()
    {
        return $this->hasMany(JadwalBooking::class);
    }
}
