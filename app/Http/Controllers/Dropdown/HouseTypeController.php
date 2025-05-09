<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Services\HouseTypeService;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{
    protected $service;

    public function __construct(HouseTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
        ]);
        return response()->json($this->service->create($validated));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
        ]);
        return response()->json($this->service->update($id, $validated));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
