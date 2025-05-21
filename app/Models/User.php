<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Favorite;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'telefon',
        'password',
        'onayli',
        'dogum_tarihi',
        'gender',
        'profile_photo_path',
        'il_id',
        'ilce_id',
        'listing_id',
        'is_helios',
    ];

    public function il()
    {
        return $this->belongsTo(IlIlceMahalle::class, 'il_id');
    }

    public function ilce()
    {
        return $this->belongsTo(IlIlceMahalle::class, 'ilce_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }



    /**
     * Attributes hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'onayli' => 'boolean',
        'character_test_done' => 'boolean',
        'dogum_tarihi' => 'date',
        'is_helios' => 'boolean'

    ];

    /**
     * Custom appended attributes (JSON response).
     */
    protected $appends = ['profile_photo_url']; // ✅ eklenen URL döner

    /**
     * JWT Identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT Custom Claims.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Accessor: Fotoğrafın tam URL'sini verir.
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
