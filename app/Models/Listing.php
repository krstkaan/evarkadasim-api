<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes; // EKLENDİ

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'rent_price',
        'square_meters',
        'roommate_gender_id',
        'age_range_id',
        'house_type_id',
        'furniture_status_id',
        'heating_type_id',
        'building_age_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


