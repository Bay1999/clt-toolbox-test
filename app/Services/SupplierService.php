<?php

namespace App\Services;

use App\Exceptions\InvalidAppFlowException;
use App\Interfaces\SupplierInterface;

use App\Exports\SuppliersExport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SupplierService
{
    private SupplierInterface $supplierInterface;

    /**
     * Create a new class instance.
     */
    public function __construct(SupplierInterface $supplierInterface)
    {
        $this->supplierInterface = $supplierInterface;
    }

    public function getAll()
    {
        return $this->supplierInterface->getAll();
    }

    public function getDataTable(?string $search = null)
    {
        $query = $this->supplierInterface->getDataTable($search);

        return DataTables::of($query)
            ->addColumn('supplier_id', function ($supplier) {
                return 'SUP-' . $supplier->created_at->format('Y') . '-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT);
            })
            ->addColumn('avatar_color', function ($supplier) {
                return $this->getAvatarColor($supplier->name);
            })
            ->addColumn('initials', function ($supplier) {
                return $this->getInitials($supplier->name);
            })
            ->editColumn('created_at', function ($supplier) {
                return $supplier->created_at->format('M d, Y');
            })
            ->rawColumns(['name']) // Just in case
            ->make(true);
    }

    public function export(?string $search = null)
    {
        $suppliers = $this->supplierInterface->getDataTable($search)->get();
        return Excel::download(new SuppliersExport($suppliers), 'suppliers_export.xlsx');
    }

    private function getAvatarColor($name)
    {
        $colors = ['blue', 'green', 'orange', 'purple', 'red', 'pink', 'indigo'];
        $hash = substr(md5($name), 0, 1);
        $index = hexdec($hash) % count($colors);
        return $colors[$index];
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    public function getById(int $id)
    {
        $supplier = $this->supplierInterface->getById($id);
        if (! $supplier) {
            throw new InvalidAppFlowException('Supplier not found');
        }
        return $supplier;
    }

    public function create(array $data)
    {
        return $this->supplierInterface->create($data);
    }

    public function update(int $id, array $data)
    {
        $this->getById($id);
        return $this->supplierInterface->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->getById($id);
        return $this->supplierInterface->delete($id);
    }

    public function getShowData(int $id)
    {
        $data = [
            'supplier' => $this->getById($id),
        ];
        return $data;
    }
}
