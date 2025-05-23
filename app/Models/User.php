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
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_pengguna',
        'email',
        'password',
        'role_id',
        'lokasi_id'
    ];

    public function role()
    {
        return $this->belongsTo(roles::class, 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function pengajuanBookings()
    {
        return $this->hasMany(PengajuanBooking::class);
    }

    public function pembatalanJadwals()
    {
        return $this->hasMany(PembatalanJadwal::class);
    }


    public function catatanAktivitas()
    {
        return $this->hasMany(CatatanAktivitas::class);
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
