<?php

namespace App\Repositories;

use App\Interfaces\CltLayerInterface;
use App\Models\CltLayer;
use Illuminate\Support\Facades\DB;
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

    public function syncLayers(int $layupId, array $layersData)
    {
        return DB::transaction(function () use ($layupId, $layersData) {
            // Get existing layer IDs to find which ones to delete
            $existingLayerIds = CltLayer::where('layup_id', $layupId)->pluck('id')->toArray();
            $incomingLayerIds = [];

            foreach ($layersData as $index => $layerData) {
                $data = [
                    'layup_id' => $layupId,
                    'layer_order' => $index + 1, // Enforce order based on array index
                    'thickness' => $layerData['thickness'],
                    'width' => $layerData['width'],
                    'angle' => $layerData['angle'],
                    'grade' => $layerData['grade'],
                ];

                if (isset($layerData['id']) && in_array((int)$layerData['id'], $existingLayerIds)) {
                    // Update existing
                    CltLayer::where('id', $layerData['id'])->update($data);
                    $incomingLayerIds[] = (int)$layerData['id'];
                } else {
                    // Create new
                    $newLayer = CltLayer::create($data);
                    $incomingLayerIds[] = $newLayer->id;
                }
            }

            // Delete layers that were removed from the UI
            $idsToDelete = array_diff($existingLayerIds, $incomingLayerIds);
            if (!empty($idsToDelete)) {
                CltLayer::whereIn('id', $idsToDelete)->delete();
            }

            return $this->getByLayupId($layupId);
        });
    }
}
