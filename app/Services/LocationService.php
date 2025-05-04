<?php

namespace App\Services;

use App\Models\IlIlceMahalle;

class LocationService
{
    public function getAllCities(): array
    {
        return IlIlceMahalle::where('UstID', 0)
            ->orderBy('SehirIlceMahalleAdi')
            ->get()
            ->toArray();
    }

    public function getDistrictsByCityId(int $cityId): array
    {
        return IlIlceMahalle::where('UstID', $cityId)
            ->orderBy('SehirIlceMahalleAdi')
            ->get()
            ->toArray();
    }
}
