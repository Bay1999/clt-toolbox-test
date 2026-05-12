<?php

namespace App\Repositories;

use App\Interfaces\CltLayerInterface;
use App\Models\CltLayer;

class CltLayerRepository implements CltLayerInterface
{
    public function getAll()
    {
        return CltLayer::all();
    }

    public function getById(int $id)
    {
        return CltLayer::find($id);
    }

    public function create(array $data)
    {
        return CltLayer::create($data);
    }

    public function update(int $id, array $data)
    {
        $layer = $this->getById($id);
        $layer->update($data);
        return $layer;
    }

    public function delete(int $id)
    {
        $layer = $this->getById($id);
        return $layer->delete();
    }

    public function getByLayupId(int $layupId)
    {
        return CltLayer::where('layup_id', $layupId)->orderBy('layer_order')->get();
    }
}
