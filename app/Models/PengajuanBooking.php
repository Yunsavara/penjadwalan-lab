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
        'mode_tanggal_pengajuan',
        'lokasi_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lokasis()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function jadwalBookings()
    {
        return $this->hasMany(JadwalBooking::class);
    }

    public function laboratorium()
    {
        return $this->hasManyThrough(
            LaboratoriumUnpam::class,
            JadwalBooking::class,
            'pengajuan_booking_id', // Foreign key di JadwalBooking
            'id',                    // Primary key di LaboratoriumUnpam
            'id',                    // Local key di PengajuanBooking
            'laboratorium_unpam_id' // Foreign key di JadwalBooking
        );
    }
}
