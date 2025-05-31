<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $fillable = [
        'rater_user_id',
        'target_user_id',
        'rating',
        'comment',
    ];

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_user_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}

