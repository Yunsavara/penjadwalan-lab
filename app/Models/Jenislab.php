<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Jenislab extends Model
{
    use HasSlug;

    protected $fillable = [
        'name_jenis_lab',
        'slug_jenis_lab',
        'description_jenis_lab'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name_jenis_lab')
            ->saveSlugsTo('slug_jenis_lab');
    }

    public function LaboratoriumUnpams()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'jenislab_id');
    }
}
