<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaktuOperasional extends Model
{
    protected $fillable = [
        'hari_operasional',
        'jam_mulai',
        'jam_selesai',
        'lokasi_id'
    ];

    protected $casts = [
        'hari_operasional' => 'array', // Otomatis decode JSON ke array PHP
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
