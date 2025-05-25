<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingLog extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'action',
        'description',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

