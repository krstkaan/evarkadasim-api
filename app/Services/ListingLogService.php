<?php

namespace App\Services;

use App\Models\ListingLog;

class ListingLogService
{
    public static function log(int $listingId, string $action, ?string $description = null): void
    {
        ListingLog::create([
            'listing_id' => $listingId,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
