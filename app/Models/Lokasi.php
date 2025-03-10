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
}
