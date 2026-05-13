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

    public function exportTemplateBySupplier(int $supplierId)
    {
        $layups = $this->cltLayupInterface->getBySupplierId($supplierId)
            ->with('cltLayers')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CltLayupTemplateExport($supplierId, $layups), 
            'layup_template_' . now()->format('YmdHis') . '.xlsx'
        );
    }

    public function import(int $supplierId, $file)
    {
        $sheets = \Maatwebsite\Excel\Facades\Excel::toArray(new class {}, $file);
        
        if (count($sheets) < 2) {
            throw new InvalidAppFlowException('Invalid template format. Expected at least 2 sheets.');
        }

        $layupsSheet = $sheets[0];
        $layersSheet = $sheets[1];

        $processedData = [];
        $hasAnyConflict = false;

        // Process Layups (Sheet 1)
        foreach ($layupsSheet as $index => $row) {
            if ($index === 0) continue; // Skip header

            $layupName = $row[0] ?? null;
            $layupGrade = $row[1] ?? null;
            $isActive = $row[2] ?? 0;

            if (empty($layupName)) continue;

            $existingLayup = \App\Models\CltLayup::where('name', $layupName)
                ->where('supplier_id', $supplierId)
                ->first();

            $processedData[$layupName] = [
                'name' => $layupName,
                'grade' => $layupGrade,
                'is_active' => $isActive,
                'exists' => (bool)$existingLayup,
                'has_conflict' => false,
                'layers' => []
            ];
        }

        // Process Layers (Sheet 2)
        foreach ($layersSheet as $index => $row) {
            if ($index === 0) continue; // Skip header

            $layupName = $row[0] ?? null;
            if (!$layupName || !isset($processedData[$layupName])) continue;

            $layerOrder = $row[1] ?? null;
            $thickness = $row[2] ?? null;
            $width = $row[3] ?? null;
            $angle = $row[4] ?? null;
            $layerGrade = $row[5] ?? null;

            if (empty($layerOrder)) continue;

            $processedData[$layupName]['layers'][$layerOrder] = [
                'order' => $layerOrder,
                'thickness' => $thickness,
                'width' => $width,
                'angle' => $angle,
                'grade' => $layerGrade,
                'is_conflict' => false,
                'current' => null
            ];
        }

        // Check for conflicts
        foreach ($processedData as $name => &$data) {
            if ($data['exists']) {
                $existingLayup = \App\Models\CltLayup::where('name', $name)
                    ->where('supplier_id', $supplierId)
                    ->with('cltLayers')
                    ->first();

                $existingLayers = $existingLayup->cltLayers->keyBy('layer_order');

                foreach ($data['layers'] as $order => &$layerData) {
                    if ($existingLayers->has($order)) {
                        $currentLayer = $existingLayers->get($order);
                        $layerData['current'] = [
                            'thickness' => $currentLayer->thickness,
                            'width' => $currentLayer->width,
                            'angle' => $currentLayer->angle,
                            'grade' => $currentLayer->grade
                        ];

                        if (
                            $currentLayer->thickness != $layerData['thickness'] ||
                            $currentLayer->width != $layerData['width'] ||
                            $currentLayer->angle != $layerData['angle']
                        ) {
                            $layerData['is_conflict'] = true;
                            $data['has_conflict'] = true;
                            $hasAnyConflict = true;
                        }
                    }
                }
                
                // Check if there are layers in DB that are NOT in Excel
                // (Optional: depending on requirements. For now, let's focus on existing layers).
            }
        }

        if ($hasAnyConflict) {
            return [
                'status' => 'conflicts',
                'data' => array_values($processedData)
            ];
        }

        // No conflicts, perform the import immediately
        return \Illuminate\Support\Facades\DB::transaction(function () use ($supplierId, $processedData) {
            foreach ($processedData as $data) {
                $layup = \App\Models\CltLayup::firstOrNew([
                    'name' => $data['name'], 
                    'supplier_id' => $supplierId
                ]);

                $isNewLayup = !$layup->exists;
                $layup->grade = $data['grade'];
                $layup->is_active = $data['is_active'];

                if ($isNewLayup) {
                    $layup->revision_counter = 1;
                }
                
                $layup->save();

                $hasChanges = false;
                foreach ($data['layers'] as $layerData) {
                    $layer = \App\Models\CltLayer::updateOrCreate(
                        ['layup_id' => $layup->id, 'layer_order' => $layerData['order']],
                        [
                            'thickness' => $layerData['thickness'],
                            'width' => $layerData['width'],
                            'angle' => $layerData['angle'],
                            'grade' => $layerData['grade']
                        ]
                    );

                    if ($layer->wasRecentlyCreated || $layer->wasChanged()) {
                        $hasChanges = true;
                    }
                }

                if (!$isNewLayup && ($layup->wasChanged() || $hasChanges)) {
                    $layup->increment('revision_counter');
                }
            }
            return ['status' => 'success'];
        });
    }

    public function resolveImport(int $supplierId, array $resolutions)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($supplierId, $resolutions) {
            foreach ($resolutions as $res) {
                $layup = \App\Models\CltLayup::firstOrNew([
                    'name' => $res['name'], 
                    'supplier_id' => $supplierId
                ]);

                $isNewLayup = !$layup->exists;
                $layup->grade = $res['grade'];
                $layup->is_active = $res['is_active'];

                if ($isNewLayup) {
                    $layup->revision_counter = 1;
                }
                
                $layup->save();

                $hasChanges = false;
                foreach ($res['layers'] as $layerData) {
                    // $layerData contains 'order', 'thickness', 'width', 'angle', 'grade', and 'action' (keep/new)
                    if (($layerData['action'] ?? 'new') === 'new') {
                        $layer = \App\Models\CltLayer::updateOrCreate(
                            ['layup_id' => $layup->id, 'layer_order' => $layerData['order']],
                            [
                                'thickness' => $layerData['thickness'],
                                'width' => $layerData['width'],
                                'angle' => $layerData['angle'],
                                'grade' => $layerData['grade']
                            ]
                        );

                        if ($layer->wasRecentlyCreated || $layer->wasChanged()) {
                            $hasChanges = true;
                        }
                    }
                }

                if (!$isNewLayup && ($layup->wasChanged() || $hasChanges)) {
                    $layup->increment('revision_counter');
                }
            }
            return ['status' => 'success'];
        });
    }
}
