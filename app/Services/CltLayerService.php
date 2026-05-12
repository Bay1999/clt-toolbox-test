<?php

namespace App\Services;

use App\Exceptions\InvalidAppFlowException;
use App\Interfaces\CltLayerInterface;

class CltLayerService
{
    private CltLayerInterface $cltLayerInterface;

    public function __construct(CltLayerInterface $cltLayerInterface)
    {
        $this->cltLayerInterface = $cltLayerInterface;
    }

    public function getById(int $id)
    {
        $layer = $this->cltLayerInterface->getById($id);
        if (!$layer) {
            throw new InvalidAppFlowException('Layer not found');
        }
        return $layer;
    }

    public function create(array $data)
    {
        return $this->cltLayerInterface->create($data);
    }

    public function update(int $id, array $data)
    {
        $this->getById($id);
        return $this->cltLayerInterface->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->getById($id);
        return $this->cltLayerInterface->delete($id);
    }

    public function getByLayupId(int $layupId)
    {
        return $this->cltLayerInterface->getByLayupId($layupId);
    }
}
