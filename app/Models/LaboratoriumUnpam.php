<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumUnpam extends Model
{

    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'lokasi',
        'kapasitas',
        'status',
        'jenislab_id'
    ];

    // Relasi buat ke jenis lab
    public function Jenislab()
    {
        return $this->belongsTo(Jenislab::class, 'jenislab_id');
    }

    // Relasi ke Booking Detail (Pengajuan yang terkait dengan lab ini)
    public function bookingDetail()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
