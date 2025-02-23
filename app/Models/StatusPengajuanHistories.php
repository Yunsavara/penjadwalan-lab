<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengajuanHistories extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'status'
    ];

    // Relasi ke User (pemilik pengajuan/jadwal)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User (yang mengubah status)
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Relasi ke Laboratorium
    public function laboratorium()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'lab_id');
    }

    // Relasi ke Pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    // Relasi ke Jadwal (jika ada)
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'kode_pengajuan', 'kode_pengajuan');
    }
}
