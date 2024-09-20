<?php

namespace Ispgo\Mikrotik\Http\Controller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Services\IPPoolManager;
use Exception;

class PoolController extends Controller
{
    protected IPPoolManager $ipPoolManager;

    public function __construct(IPPoolManager $ipPoolManager)
    {
        $this->ipPoolManager = $ipPoolManager;
    }

    /**
     * Obtener todos los pools de IP.
     */
    public function getPools()
    {
        try {
            $pools = $this->ipPoolManager->listPools();
            return response()->json(['data' => $pools], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un nuevo pool de IP.
     */
    public function createPool(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'ranges' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        try {
            $pool = $this->ipPoolManager->createPool($request->name, $request->ranges, $request->comment);
            return response()->json(['data' => $pool], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un pool de IP.
     */
    public function deletePool($id)
    {
        try {
            $this->ipPoolManager->deletePool($id);
            return response()->json(['message' => 'Pool deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
