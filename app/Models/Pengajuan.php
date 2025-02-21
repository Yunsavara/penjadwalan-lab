<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{


    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kode_pengajuan', 'kode_pengajuan');
    }

}
