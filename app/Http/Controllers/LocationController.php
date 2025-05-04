<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocationService;

class LocationController extends Controller
{
    protected LocationService $service;

    public function __construct(LocationService $service)
    {
        $this->service = $service;
    }

    public function cities()
    {
        return response()->json($this->service->getAllCities());
    }

    public function districts($cityId)
    {
        return response()->json($this->service->getDistrictsByCityId($cityId));
    }
}
