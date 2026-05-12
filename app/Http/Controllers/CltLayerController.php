<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidAppFlowException;
use App\Models\CltLayer;
use App\Services\CltLayerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CltLayerController extends Controller
{
    private CltLayerService $cltLayerService;

    public function __construct(CltLayerService $cltLayerService)
    {
        $this->cltLayerService = $cltLayerService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'layup_id' => 'required|exists:clt_layups,id',
                'layer_order' => 'required|integer',
                'thickness' => 'required|numeric',
                'width' => 'required|numeric',
                'angle' => 'required|numeric',
                'grade' => 'required|string|max:255',
            ]);

            $layer = $this->cltLayerService->create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $layer
            ], 201);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create layer: ' . $e->getMessage()
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, CltLayer $cltLayer)
    {
        try {
            $validated = $request->validate([
                'layer_order' => 'required|integer',
                'thickness' => 'required|numeric',
                'width' => 'required|numeric',
                'angle' => 'required|numeric',
                'grade' => 'required|string|max:255',
            ]);

            $updatedLayer = $this->cltLayerService->update($cltLayer->id, $validated);

            return response()->json([
                'status' => 'success',
                'data' => $updatedLayer
            ], 200);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update layer: ' . $e->getMessage()
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
    public function destroy(CltLayer $cltLayer)
    {
        try {
            $this->cltLayerService->delete($cltLayer->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Layer deleted successfully'
            ], 204);
        } catch (InvalidAppFlowException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete layer: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
