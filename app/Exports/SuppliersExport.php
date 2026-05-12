<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuppliersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $suppliers;

    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function collection()
    {
        return $this->suppliers;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Total Layups',
            'Created At',
        ];
    }

    public function map($supplier): array
    {
        return [
            'SUP-' . $supplier->created_at->format('Y') . '-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT),
            $supplier->name,
            $supplier->clt_layups_count,
            $supplier->created_at->format('M d, Y'),
        ];
    }
}
