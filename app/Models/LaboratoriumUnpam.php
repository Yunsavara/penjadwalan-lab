<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoriumUnpam extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name_laboratorium',
        'lokasi_id',
        'kapasitas_laboratorium',
        'status_laboratorium',
        'jenislab_id'
    ];

    // Relasi buat ke jenis lab
    public function Jenislab()
    {
        return $this->belongsTo(Jenislab::class, 'jenislab_id');
    }

    // Relasi ke Booking Detail (Pengajuan yang terkait dengan lab ini)
    public function bookingDetail()
    {
        return $this->hasMany(BookingDetail::class);
    }

    // Relasi ke Lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}
