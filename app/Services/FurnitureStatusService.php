<?php

namespace App\Services;

use App\Models\FurnitureStatus;

class FurnitureStatusService
{
    public function all()
    {
        return FurnitureStatus::all();
    }

    public function find($id)
    {
        return FurnitureStatus::findOrFail($id);
    }

    public function create(array $data)
    {
        return FurnitureStatus::create($data);
    }

    public function update($id, array $data)
    {
        $status = FurnitureStatus::findOrFail($id);
        $status->update($data);
        return $status;
    }

    public function delete($id)
    {
        return FurnitureStatus::destroy($id);
    }
}
