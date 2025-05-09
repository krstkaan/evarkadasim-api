<?php

namespace App\Services;

use App\Models\HeatingType;

class HeatingTypeService
{
    public function all()
    {
        return HeatingType::all();
    }

    public function find($id)
    {
        return HeatingType::findOrFail($id);
    }

    public function create(array $data)
    {
        return HeatingType::create($data);
    }

    public function update($id, array $data)
    {
        $type = HeatingType::findOrFail($id);
        $type->update($data);
        return $type;
    }

    public function delete($id)
    {
        return HeatingType::destroy($id);
    }
}