<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
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
        'building_age_id'
    ];

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }
}

