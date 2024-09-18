<?php

namespace Ispgo\Mikrotik\Http\Controller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Services\DHCPv6Manager;
use Exception;

class DHCPv6Controller extends Controller
{
    protected DHCPv6Manager $dhcpv6Manager;

    public function __construct(DHCPv6Manager $DHCPv6Manager)
    {
        $this->dhcpv6Manager = $DHCPv6Manager;
    }

    /**
     * Obtener todos los servidores DHCPv6.
     */
    public function getDHCPs()
    {
        try {
            $dhcps = $this->dhcpv6Manager->listDHCPs();
            return response()->json(['data' => $dhcps], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un nuevo servidor DHCPv6.
     */
    public function createDHCP(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'interface' => 'required|string',
            'pool' => 'required|string',
        ]);

        try {
            $dhcp = $this->dhcpv6Manager->createDHCP(
                $request->name,
                $request->interface,
                $request->pool
            );
            return response()->json(['data' => $dhcp], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un servidor DHCPv6.
     */
    public function deleteDHCP($id)
    {
        try {
            $this->dhcpv6Manager->deleteDHCP($id);
            return response()->json(['message' => 'DHCPv6 deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
