<?php

namespace App\Repositories;

use App\Interfaces\CltLayupInterface;
use App\Models\CltLayup;
use Illuminate\Database\Eloquent\Builder;

class CltLayupRepository implements CltLayupInterface
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return CltLayup::all();
    }
 
    public function getById(int $id): CltLayup
    {
        return CltLayup::withTrashed()->findOrFail($id);
    }
 
    public function create(array $data): CltLayup
    {
        return CltLayup::create($data);
    }
 
    public function update(int $id, array $data): CltLayup
    {
        $layup = $this->getById($id);
        
        // Only increment if name or grade is being updated
        if (isset($data['name']) || isset($data['grade'])) {
            $data['revision_counter'] = $layup->revision_counter + 1;
        }
 
        $layup->update($data);
        return $layup;
    }
 
    public function delete(int $id): bool
    {
        $layup = $this->getById($id);
        return $layup->delete();
    }
 
    public function restore(int $id): bool
    {
        $layup = $this->getById($id);
        return $layup->restore();
    }

    public function getBySupplierId(int $supplierId, ?string $search = null): Builder
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

    public function incrementRevision(int $id): CltLayup
    {
        $layup = $this->getById($id);
        $layup->increment('revision_counter');
        return $layup;
    }
}
