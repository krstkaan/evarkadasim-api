<?php

namespace App\Services;

use App\Models\BuildingAge;

class BuildingAgeService
{
    public function all()
    {
        return BuildingAge::all();
    }

    public function find($id)
    {
        return BuildingAge::findOrFail($id);
    }

    public function create(array $data)
    {
        return BuildingAge::create($data);
    }

    public function update($id, array $data)
    {
        $age = BuildingAge::findOrFail($id);
        $age->update($data);
        return $age;
    }

    public function delete($id)
    {
        return BuildingAge::destroy($id);
    }
}
