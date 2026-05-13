<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class SupplierSheetExport implements FromView, WithTitle, WithStyles
{
    protected $supplier;

    public function __construct($supplier)
    {
        $this->supplier = $supplier;
    }

    public function view(): View
    {
        return view('exports.supplier_details', [
            'supplier' => $this->supplier
        ]);
    }

    public function title(): string
    {
        return substr($this->supplier->name, 0, 31); // Excel sheet titles max 31 chars
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold header labels
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
