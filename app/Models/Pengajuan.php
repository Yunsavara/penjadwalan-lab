<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{

    protected $fillable = [
        'kode_pengajuan',
        'keperluan',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kode_pengajuan', 'kode_pengajuan');
    }

}
