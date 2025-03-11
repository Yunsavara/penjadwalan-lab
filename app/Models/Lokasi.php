<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'name'
    ];

    public function waktuOperasional()
    {
        return $this->hasMany(WaktuOperasional::class);
    }

    public function laboratoriumUnpam()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'lokasi_id');
    }
}
