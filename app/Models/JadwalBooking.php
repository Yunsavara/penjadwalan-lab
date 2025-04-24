<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalBooking extends Model
{
    protected $fillable = [
        'tanggal_jadwal',
        'jam_mulai',
        'jam_selesai',
        'status_jadwal_booking',
        'pengajuan_booking_id',
        'laboratorium_unpam_id'
    ];

    public function pengajuanBooking()
    {
        return $this->belongsTo(PengajuanBooking::class);
    }

    public function laboratoriumUnpam()
    {
        return $this->belongsTo(LaboratoriumUnpam::class);
    }

    public function aktivitas()
    {
        return $this->morphMany(CatatanAktivitas::class, 'table');
    }
}
