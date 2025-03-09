<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    protected $fillable = [
        'booking_detail_id',
        'user_id',
        'status',
        'catatan'
    ];

    public function bookingDetail()
    {
        return $this->belongsTo(BookingDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
