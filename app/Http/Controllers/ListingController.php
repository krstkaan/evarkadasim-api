<?php

namespace App\Http\Controllers;

use App\Services\ListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    protected $service;

    public function __construct(ListingService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rent_price' => 'required|numeric|min:0',
            'square_meters' => 'required|integer|min:1',

            'roommate_gender_id' => 'required|exists:roommate_genders,id',
            'age_range_id' => 'required|exists:age_ranges,id',
            'house_type_id' => 'required|exists:house_types,id',
            'furniture_status_id' => 'required|exists:furniture_statuses,id',
            'heating_type_id' => 'required|exists:heating_types,id',
            'building_age_id' => 'required|exists:building_ages,id',

            'images' => 'required|array|min:1|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $listing = $this->service->create($validated, $request->file('images'), Auth::id());
            return response()->json($listing, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function myListing()
    {
        return response()->json($this->service->getByUser(Auth::id()));
    }

    public function destroy($id)
    {
        $this->service->delete($id, Auth::id());
        return response()->json(['message' => 'İlan silindi.']);
    }
    public function index()
    {
        $listings = $this->service->getAllExceptUser(auth()->id());
        return response()->json($listings);
    }
}
