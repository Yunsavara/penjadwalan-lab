<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'name',
        'kode_pengajuan',
        'keperluan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'lab_id',
        'user_id'
    ];

    public function laboratoriumUnpam()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'lab_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'kode_pengajuan', 'kode_pengajuan');
    }


}
