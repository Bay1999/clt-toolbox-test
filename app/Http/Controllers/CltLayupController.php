<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\InvalidAppFlowException;
use App\Services\CltLayupService;
use Illuminate\Support\Facades\Log;

class CltLayupController extends Controller
{
    private CltLayupService $cltLayupService;

    public function __construct(CltLayupService $cltLayupService)
    {
        $this->cltLayupService = $cltLayupService;
    }

    public function getData(Request $request)
    {
        $supplierId = $request->query('supplier_id');
        return $this->cltLayupService->getDataTable($supplierId, $request->query('search'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'name' => 'required|string|max:255',
                'grade' => 'required|string|max:255',
            ]);

            $layup = $this->cltLayupService->create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $layup
            ], 201);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create layup: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CltLayup $cltLayup)
    {
        return response()->json($cltLayup);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CltLayup $cltLayup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'grade' => 'required|string|max:255',
            ]);

            $updatedLayup = $this->cltLayupService->update($id, $validated);

            return response()->json([
                'status' => 'success',
                'data' => $updatedLayup
            ], 200);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update layup: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->cltLayupService->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Layup archived successfully'
            ], 200);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to archive layup: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $isActive = $request->input('is_active');
            $this->cltLayupService->updateStatus($id, $isActive);

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $this->cltLayupService->restore($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Layup restored successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
