<?php

namespace App\Repositories;

use App\Interfaces\SupplierInterface;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SupplierRepository implements SupplierInterface
{
    public function getAll(): Collection
    {
        return Supplier::get();
    }

    public function getDataTable(?string $search = null): Builder
    {
        $query = Supplier::withCount('cltLayups');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orHas('cltLayups', '=', (int) $search);
                }
            });
        }

        return $query->latest();
    }

    public function getById(int $id): Supplier
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(int $id, array $data): Supplier
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete(int $id): Supplier
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return $supplier;
    }
}
