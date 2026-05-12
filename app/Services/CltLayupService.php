<?php

namespace App\Services;

use App\Exceptions\InvalidAppFlowException;
use App\Interfaces\CltLayupInterface;
use Yajra\DataTables\Facades\DataTables;

class CltLayupService
{
    private CltLayupInterface $cltLayupInterface;

    public function __construct(CltLayupInterface $cltLayupInterface)
    {
        $this->cltLayupInterface = $cltLayupInterface;
    }

    public function getById(int $id)
    {
        $layup = $this->cltLayupInterface->getById($id);
        if (!$layup) {
            throw new InvalidAppFlowException('Layup not found');
        }
        return $layup;
    }

    public function create(array $data)
    {
        $data['revision_counter'] = 1;
        $data['is_active'] = 0; // Draft
        return $this->cltLayupInterface->create($data);
    }

    public function update(int $id, array $data)
    {
        $this->getById($id);
        return $this->cltLayupInterface->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->getById($id);
        return $this->cltLayupInterface->delete($id);
    }

    public function restore(int $id)
    {
        return $this->cltLayupInterface->restore($id);
    }

    public function updateStatus(int $id, int $isActive)
    {
        $this->getById($id);
        return $this->cltLayupInterface->update($id, ['is_active' => $isActive]);
    }

    public function getDataTable(int $supplierId, ?string $search = null)
    {
        $query = $this->cltLayupInterface->getBySupplierId($supplierId, $search);

        return DataTables::of($query)
            ->addColumn('thickness', function ($layup) {
                return ($layup->total_thickness ?? 0) . ' mm';
            })
            ->addColumn('ply_count', function ($layup) {
                return $layup->clt_layers_count;
            })
            ->editColumn('revision_counter', function ($layup) {
                $date = $layup->updated_at->format('M Y');
                return "Rev {$layup->revision_counter} ({$date})";
            })
            ->editColumn('is_active', function ($layup) {
                if ($layup->deleted_at) {
                    return 'Archived';
                }
                return $layup->is_active ? 'Active' : 'Draft';
            })
            ->make(true);
    }
}
