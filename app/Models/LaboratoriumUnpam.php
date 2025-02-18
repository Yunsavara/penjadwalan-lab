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

    public function Jenislab()
    {
        return $this->belongsTo(Jenislab::class, 'jenislab_id');
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
