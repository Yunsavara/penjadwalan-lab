<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;

class MataKuliah extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'semester_id'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }



    // Many to Many ke User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_mata_kuliah');
    }
}
