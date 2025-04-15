<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenislab extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name_jenis_lab',
        'slug_jenis_lab',
        'description_jenis_lab'
    ];


    public function LaboratoriumUnpams()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'jenislab_id');
    }
}
