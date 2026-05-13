<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LayupsTemplateSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $supplierId;
    protected $layups;

    public function __construct($supplierId, $layups)
    {
        $this->supplierId = $supplierId;
        $this->layups = $layups;
    }

    public function collection()
    {
        return $this->layups->isNotEmpty() ? $this->layups : collect([null]);
    }

    public function headings(): array
    {
        return [
            'Layup Name',
            'Layup Grade',
            'Is Active (1/0)',
        ];
    }

    public function map($layup): array
    {
        if (!$layup) {
            return ['', '', ''];
        }

        return [
            $layup->name,
            $layup->grade,
            $layup->is_active,
        ];
    }

    public function title(): string
    {
        return 'Layups';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
