<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'kode_pengajuan',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingDetail()
    {
        return $this->hasMany(BookingDetail::class, 'kode_pengajuan', 'kode_pengajuan');
    }
}
