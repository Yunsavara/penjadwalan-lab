<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanAktivitas extends Model
{
    protected $fillable = [
        'aksi',
        'table_type',
        'table_id',
        'deskripsi',
        'user_id'
    ];

    public function table()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
