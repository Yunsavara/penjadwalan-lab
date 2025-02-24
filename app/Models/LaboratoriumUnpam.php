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

    // Relasi ke Pengajuan (Pengajuan yang terkait dengan lab ini)
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'lab_id', 'id');
    }

    // Relasi ke Jadwal (Jadwal yang terkait dengan lab ini)
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'lab_id', 'id');
    }


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Barang
    // public function laboratorium()
    // {
    //     return $this->belongsTo(Laboratorium::class, 'laboratorium_id');
    // }

    // Disini
    // public function barang()
    // {
    //     return $this->hasOne(Barang::class, 'laboratorium_id');
    // }
}
