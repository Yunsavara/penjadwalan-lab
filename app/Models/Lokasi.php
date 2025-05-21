<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lokasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_lokasi',
        'deskripsi_lokasi'
    ];

    public function laboratoriumUnpam()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'lokasi_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hariOperasionals()
    {
        return $this->hasMany(HariOperasional::class);
    }

    public function pengajuanBookings()
    {
        return $this->hasMany(PengajuanBooking::class);
    }
}
