<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LayersTemplateSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $layups;

    public function __construct($layups)
    {
        $this->layups = $layups;
    }

    public function collection()
    {
        $layers = collect();

        foreach ($this->layups as $layup) {
            // Sort layers by layer_order for each layup
            $sortedLayers = $layup->cltLayers->sortBy('layer_order');
            
            foreach ($sortedLayers as $layer) {
                $layer->setRelation('cltLayup', $layup);
                $layers->push($layer);
            }
        }

        return $layers->isNotEmpty() ? $layers : collect([null]);
    }

    public function headings(): array
    {
        return [
            'Layup Name',
            'Layer Order',
            'Thickness (mm)',
            'Width (mm)',
            'Angle (0/90)',
            'Layer Grade',
        ];
    }

    public function map($layer): array
    {
        if (!$layer) {
            return ['', '', '', '', '', ''];
        }

        return [
            $layer->cltLayup->name ?? '',
            $layer->layer_order,
            $layer->thickness,
            $layer->width,
            $layer->angle,
            $layer->grade,
        ];
    }

    public function title(): string
    {
        return 'Layers';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
