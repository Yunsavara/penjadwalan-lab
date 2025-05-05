<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariOperasional extends Model
{
    protected $fillable = [
        'hari_operasional',
        'lokasi_id',
        'is_disabled',
    ];

    public function jamOperasionals()
    {
        return $this->hasMany(JamOperasional::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
