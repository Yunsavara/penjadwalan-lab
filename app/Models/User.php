<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'slug',
        'role_id'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function role()
    {
        return $this->belongsTo(roles::class, 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    // Relasi ke Pengajuan (User yang mengajukan jadwal)
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'user_id', 'id');
    }

    // Relasi ke Jadwal (Jadwal yang sudah diterima)
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'user_id', 'id');
    }

    // Relasi ke Pengajuan Status Histories (Tracking perubahan status pengajuan oleh user )
    public function pengajuanStatusHistories()
    {
        return $this->hasMany(StatusPengajuanHistories::class, 'changed_by', 'id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
