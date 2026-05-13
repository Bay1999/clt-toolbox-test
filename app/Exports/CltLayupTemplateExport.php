<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CltLayupTemplateExport implements WithMultipleSheets
{
    protected $supplierId;
    protected $layups;

    public function __construct($supplierId, $layups)
    {
        $this->supplierId = $supplierId;
        $this->layups = $layups;
    }

    public function sheets(): array
    {
        return [
            new LayupsTemplateSheet($this->supplierId, $this->layups),
            new LayersTemplateSheet($this->layups),
        ];
    }
}
