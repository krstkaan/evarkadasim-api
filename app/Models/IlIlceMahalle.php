<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IlIlceMahalle extends Model
{
    protected $table = 'ililcemahalle';

    protected $fillable = [
        'SehirIlceMahalleAdi',
        'UstID',
        'minlongitude',
        'minlatitude',
        'maxlongitude',
        'maxlatitude',
        'MahalleID',
    ];

    public function altlar()
    {
        return $this->hasMany(self::class, 'UstID', 'id');
    }

    public function ust()
    {
        return $this->belongsTo(self::class, 'UstID', 'id');
    }
}

