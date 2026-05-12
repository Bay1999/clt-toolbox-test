<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Exceptions\InvalidAppFlowException;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('supplier.index');
    }

    public function getData(\Illuminate\Http\Request $request)
    {
        return $this->supplierService->getDataTable($request->query('search'));
    }

    public function export(\Illuminate\Http\Request $request)
    {
        return $this->supplierService->export($request->query('search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        try {
            $validData = $request->validated();

            $supplier = $this->supplierService->create($validData);

            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ], 201);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create supplier ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::Error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $data = $this->supplierService->getShowData($supplier->id);

        return view('supplier.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            $validData = $request->validated();

            $updatedSupplier = $this->supplierService->update($supplier->id, $validData);

            return response()->json([
                'status' => 'success',
                'data' => $updatedSupplier
            ], 200);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update supplier ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::Error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->delete($supplier->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully'
            ], 204);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete supplier causes by ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::Error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
