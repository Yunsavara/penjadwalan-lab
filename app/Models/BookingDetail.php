<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{

    protected $fillable = [
        'kode_pengajuan',
        'lab_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'keperluan'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function laboratorium()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'lab_id');
    }

    public function bookingLog()
    {
        return $this->hasMany(BookingLog::class);
    }
}
