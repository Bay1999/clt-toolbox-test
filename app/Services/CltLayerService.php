<?php

namespace App\Services;

use App\Exceptions\InvalidAppFlowException;
use App\Interfaces\CltLayerInterface;
use App\Interfaces\CltLayupInterface;
use Illuminate\Support\Facades\DB;

class CltLayerService
{
    private CltLayerInterface $cltLayerInterface;
    private CltLayupInterface $cltLayupInterface;

    public function __construct(CltLayerInterface $cltLayerInterface, CltLayupInterface $cltLayupInterface)
    {
        $this->cltLayerInterface = $cltLayerInterface;
        $this->cltLayupInterface = $cltLayupInterface;
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

    public function syncLayers(int $layupId, array $layersData)
    {
        return DB::transaction(function () use ($layupId, $layersData) {
            try {
                $result = $this->cltLayerInterface->syncLayers($layupId, $layersData);
                
                // Increment layup revision counter
                $this->cltLayupInterface->incrementRevision($layupId);
                
                return $result;
            } catch (\Exception $e) {
                throw new InvalidAppFlowException('Failed to sync layers: ' . $e->getMessage());
            }
        });
    }
}
