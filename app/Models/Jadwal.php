<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'name',
        'kode_booking',
        'keperluan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'lab_id',
        'user_id'
    ];

    public function laboratoriumUnpam()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'lab_id');
    }

    public function users()
    {
        return $this->belongsTo(LaboratoriumUnpam::class, 'user_id');
    }

}
