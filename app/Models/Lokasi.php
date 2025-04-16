<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'nama_lokasi'
    ];

    public function waktuOperasional()
    {
        return $this->hasMany(WaktuOperasional::class);
    }

    public function laboratoriumUnpam()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'lokasi_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
