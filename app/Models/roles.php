<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{


    protected $fillable = [
        'nama_peran',
        'prioritas_peran'
    ];

    public function user(){
        return $this->hasMany(User::class, 'role_id');
    }
}
