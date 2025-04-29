<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    protected $fillable = [
        'hari_operasional_id',
        'jam_mulai',
        'jam_selesai',
        'is_disabled',
    ];

    public function hariOperasional()
    {
        return $this->belongsTo(HariOperasional::class);
    }
}
