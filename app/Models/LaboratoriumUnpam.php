<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoriumUnpam extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'nama_laboratorium',
        'lokasi_id',
        'kapasitas_laboratorium',
        'status_laboratorium',
        'jenislab_id',
        'deskripsi_laboratorium'
    ];

    // Relasi buat ke jenis lab
    public function Jenislab()
    {
        return $this->belongsTo(Jenislab::class, 'jenislab_id');
    }

    // Relasi ke Lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function jadwalBookings()
    {
        return $this->hasMany(JadwalBooking::class);
    }
}
