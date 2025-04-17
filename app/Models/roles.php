<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class roles extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_peran',
        'prioritas_peran'
    ];

    public function user(){
        return $this->hasMany(User::class, 'role_id');
    }
}
