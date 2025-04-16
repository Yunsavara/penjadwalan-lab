<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenislab extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'nama_jenis_lab',
        'deskripsi_jenis_lab'
    ];


    public function LaboratoriumUnpams()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'jenislab_id');
    }
}
