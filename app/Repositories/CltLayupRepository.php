<?php

namespace App\Repositories;

use App\Interfaces\CltLayupInterface;
use App\Models\CltLayup;

class CltLayupRepository implements CltLayupInterface
{
    public function getAll()
    {
        return CltLayup::all();
    }

    public function getById(int $id)
    {
        return CltLayup::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return CltLayup::create($data);
    }

    public function update(int $id, array $data)
    {
        $layup = $this->getById($id);
        
        // Only increment if name or grade is being updated
        if (isset($data['name']) || isset($data['grade'])) {
            $data['revision_counter'] = $layup->revision_counter + 1;
        }

        $layup->update($data);
        return $layup;
    }

    public function delete(int $id)
    {
        $layup = $this->getById($id);
        return $layup->delete();
    }

    public function restore(int $id)
    {
        $layup = $this->getById($id);
        return $layup->restore();
    }

    public function getBySupplierId(int $supplierId, ?string $search = null)
    {
        $query = CltLayup::withTrashed()
            ->where('supplier_id', $supplierId)
            ->withCount('cltLayers')
            ->withSum('cltLayers as total_thickness', 'thickness');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
