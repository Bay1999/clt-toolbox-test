<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SuppliersExport implements WithMultipleSheets
{
    protected $suppliers;

    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->suppliers as $supplier) {
            $sheets[] = new SupplierSheetExport($supplier);
        }

        return $sheets;
    }
}
