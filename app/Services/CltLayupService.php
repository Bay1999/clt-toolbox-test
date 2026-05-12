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
        try {
            $layup = $this->cltLayupInterface->getById($id);
            if (!$layup) {
                throw new InvalidAppFlowException('Layup not found');
            }
            return $layup;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new InvalidAppFlowException('Layup not found');
        }
    }

    public function create(array $data)
    {
        try {
            $data['revision_counter'] = 1;
            $data['is_active'] = 0; // Draft
            return $this->cltLayupInterface->create($data);
        } catch (\Exception $e) {
            throw new InvalidAppFlowException('Failed to create layup: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data)
    {
        $this->getById($id);
        try {
            return $this->cltLayupInterface->update($id, $data);
        } catch (\Exception $e) {
            throw new InvalidAppFlowException('Failed to update layup: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        $this->getById($id);
        try {
            return $this->cltLayupInterface->delete($id);
        } catch (\Exception $e) {
            throw new InvalidAppFlowException('Failed to archive layup: ' . $e->getMessage());
        }
    }

    public function restore(int $id)
    {
        $this->getById($id);
        try {
            return $this->cltLayupInterface->restore($id);
        } catch (\Exception $e) {
            throw new InvalidAppFlowException('Failed to restore layup: ' . $e->getMessage());
        }
    }

    public function updateStatus(int $id, int $isActive)
    {
        $this->getById($id);
        try {
            return $this->cltLayupInterface->update($id, ['is_active' => $isActive]);
        } catch (\Exception $e) {
            throw new InvalidAppFlowException('Failed to update status: ' . $e->getMessage());
        }
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

    public function getShowData(int $id)
    {
        $layup = $this->getById($id);
        $layup->load(['cltLayers' => function ($query) {
            $query->orderBy('layer_order');
        }]);

        $data = [
            'layup' => $layup,
            'supplier' => $layup->supplier,
        ];
        return $data;
    }
}
