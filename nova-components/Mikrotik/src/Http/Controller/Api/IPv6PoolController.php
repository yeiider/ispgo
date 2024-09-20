<?php

namespace Ispgo\Mikrotik\Http\Controller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Services\IPv6PoolManager;
use Exception;

class IPv6PoolController extends Controller
{
    protected IPv6PoolManager $ipv6PoolManager;

    public function __construct(IPv6PoolManager $ipv6PoolManager)
    {
        $this->ipv6PoolManager = $ipv6PoolManager;
    }

    /**
     * Obtener todos los pools de IPv6.
     */
    public function getPools()
    {
        try {
            $pools = $this->ipv6PoolManager->listPools();
            return response()->json(['data' => $pools], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un nuevo pool de IPv6.
     */
    public function createPool(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'prefix' => 'required|string',
            'prefix-length' => 'required|integer',
            'comment' => 'nullable|string',
        ]);

        try {
            $pool = $this->ipv6PoolManager->createPool(
                $request->name,
                $request->prefix,
                $request->input('prefix-length'),
                $request->comment
            );
            return response()->json(['data' => $pool], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un pool de IPv6.
     */
    public function deletePool($id)
    {
        try {
            $this->ipv6PoolManager->deletePool($id);
            return response()->json(['message' => 'Pool deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
