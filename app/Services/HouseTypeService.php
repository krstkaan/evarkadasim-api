<?php

namespace App\Services;

use App\Models\HouseType;

class HouseTypeService
{
    public function all()
    {
        return HouseType::all();
    }

    public function find($id)
    {
        return HouseType::findOrFail($id);
    }

    public function create(array $data)
    {
        return HouseType::create($data);
    }

    public function update($id, array $data)
    {
        $type = HouseType::findOrFail($id);
        $type->update($data);
        return $type;
    }

    public function delete($id)
    {
        return HouseType::destroy($id);
    }
}
