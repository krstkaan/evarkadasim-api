<?php

namespace App\Services\Admin;

use App\Models\UserLog;
use App\Models\ListingLog;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LogService
{
    public static function getLogs(string $type, array $filters = [], int $perPage = 15): LengthAwarePaginator|Collection
    {
        switch ($type) {
            case 'user':
                $query = UserLog::with('user')->latest();

                if (isset($filters['user_id'])) {
                    $query->where('user_id', $filters['user_id']);
                }

                if (isset($filters['action'])) {
                    $query->where('action', $filters['action']);
                }

                // ✅ limit desteği (paginate yerine)
                if (isset($filters['limit'])) {
                    return $query->limit((int) $filters['limit'])->get();
                }

                return $query->paginate($perPage);

            case 'listing':
                $query = ListingLog::with(['user', 'listing'])->latest();

                if (isset($filters['listing_id'])) {
                    $query->where('listing_id', $filters['listing_id']);
                }

                if (isset($filters['user_id'])) {
                    $query->where('user_id', $filters['user_id']);
                }

                if (isset($filters['action'])) {
                    $query->where('action', $filters['action']);
                }

                if (isset($filters['limit'])) {
                    return $query->limit((int) $filters['limit'])->get();
                }

                return $query->paginate($perPage);

            default:
                throw new \InvalidArgumentException("Geçersiz log türü: {$type}");
        }
    }
}

