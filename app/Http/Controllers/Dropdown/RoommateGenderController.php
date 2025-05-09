<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\RoommateGenderService;

class RoommateGenderController extends Controller
{
    protected $service;

    public function __construct(RoommateGenderService $service)
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
            'name' => 'required|string|max:255',
        ]);
        return response()->json($this->service->create($validated));
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        return response()->json($this->service->update($id, $validated));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
