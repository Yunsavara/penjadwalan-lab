<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Jenislab extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function LaboratoriumUnpams()
    {
        return $this->hasMany(LaboratoriumUnpam::class, 'jenislab_id');
    }
}
