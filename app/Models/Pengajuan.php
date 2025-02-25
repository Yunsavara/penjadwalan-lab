<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{

    protected $fillable = [
        'kode_pengajuan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'catatan',
        'status',
        'user_id',
        'lab_id'
    ];

    // Relasi ke User (yang mengajukan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Jadwal (pengajuan yang sudah diterima)
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    // Relasi Laboratorium
    public function laboratorium()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'lab_id');
    }

    // Relasi ke Pengajuan Status Histories (tracking status)
    public function statusHistories()
    {
        return $this->hasMany(StatusPengajuanHistories::class, 'kode_pengajuan', 'kode_pengajuan');
    }


}
