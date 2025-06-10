<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchFeedback extends Model
{
    // Tablo adını açıkça belirtin
    protected $table = 'match_feedbacks';
    
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'listing_id',
        'roommate_request_id',
        'communication_score',
        'sharing_score',
        'overall_score',
        'would_live_again',
        'comment',
    ];

    // Cast'leri ekleyin
    protected $casts = [
        'would_live_again' => 'boolean',
        'communication_score' => 'integer',
        'sharing_score' => 'integer',
        'overall_score' => 'integer',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function roommateRequest()
    {
        return $this->belongsTo(RoommateRequest::class);
    }
}