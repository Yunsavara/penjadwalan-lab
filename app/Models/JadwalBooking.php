<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalBooking extends Model
{
    protected $fillable = [
        'tanggal_jadwal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'pengajuan_booking_id',
        'laboratorium_unpam_id',
    ];

    public function pengajuanBooking()
    {
        return $this->belongsTo(PengajuanBooking::class);
    }

    public function laboratoriumUnpam()
    {
        return $this->belongsTo(LaboratoriumUnpam::class);
    }

    public function pembatalanJadwals()
    {
        return $this->hasMany(PembatalanJadwal::class);
    }
}
