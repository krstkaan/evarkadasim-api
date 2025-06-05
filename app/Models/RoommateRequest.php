<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoommateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'user_id',
        'status',
    ];

    // İlan ilişkisi
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Kullanıcı ilişkisi (başvuran)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

