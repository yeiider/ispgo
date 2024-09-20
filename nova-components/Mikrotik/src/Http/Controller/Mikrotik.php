<?php

namespace Ispgo\Mikrotik\Http\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Helper\HelperMikrotikData;
use Ispgo\Mikrotik\MikrotikApi;

class Mikrotik extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);
        $response = $router->get('/interface/print', [
            ['type', 'ether'],
            ['type', 'vlan']
        ], '|');

        return response(["message" => $response]);
    }

    /**
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);
        $response = $router->execute('/ip/hotspot/ip-binding/add', [
            'mac-address' => '00:00:00:00:40:29',
            'type' => 'bypassed',
            'comment' => 'testcomment'
        ]);

        return response(["message" => $response]);
    }

    /**
     * @throws \Exception
     */
    public function addAdvancedSimpleQueue(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);

        // Ejemplo de parámetros avanzados para un simple queue
        $params = [
            'name' => 'AdvancedQueue',          // Nombre de la cola
            'target' => '192.168.1.20',         // IP del cliente
            'max-limit' => '100M/100M',         // 100 Mbps de bajada y subida
            'limit-at' => '100M/100M',          // Límite garantizado (opcional)
            'burst-limit' => '120M/120M',       // Límite de burst (opcional, puede eliminarse si no lo necesitas)
            'burst-threshold' => '80M/80M',     // Umbral de burst
            'burst-time' => '20/20',            // Tiempo de burst en segundos
            'comment' => 'Queue for 5GB plan',  // Comentario descriptivo
            'total-max-limit' => '5G',          // Límite total de navegación a 5GB
        ];

        // Ejecutar el comando para añadir el simple queue avanzado
        $response = $router->execute('/queue/simple/add', $params);

        return response(["message" => $response]);
    }

    /**
     * @throws \Exception
     */
    public function addPPPoEClient(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);

        // Información del plan (IP, velocidad, usuario, contraseña)
        $params = [
            'name' => 'pppoe-client1',          // Nombre del cliente PPPoE
            'interface' => 'ether1',            // Interfaz sobre la que se creará el cliente PPPoE
            'service-name' => '',               // Nombre del servicio PPPoE (opcional)
            'user' => 'pppoeuser1',             // Nombre de usuario del PPPoE
            'password' => 'password123',        // Contraseña del usuario PPPoE
            'add-default-route' => 'yes',       // Agregar la ruta predeterminada para el cliente PPPoE
            'use-peer-dns' => 'yes',            // Usar el DNS del peer PPPoE
            'max-mtu' => '1492',                // Valor máximo de MTU
            'max-mru' => '1492',                // Valor máximo de MRU
            'disabled' => 'no',                 // Habilitar el cliente PPPoE
        ];

        // Ejecutar el comando para añadir el cliente PPPoE
        $response = $router->execute('/interface/pppoe-client/add', $params);

        // Crear una simple queue para limitar la velocidad de navegación (100Mbps en este caso)
        $queueParams = [
            'name' => 'PPPoE-Queue',            // Nombre de la simple queue
            'target' => '192.168.1.30',         // IP del cliente PPPoE
            'max-limit' => '100M/100M',         // Velocidad de navegación (download/upload)
        ];
        $queueResponse = $router->execute('/queue/simple/add', $queueParams);

        return response([
            "pppoe_client" => $response,
            "queue" => $queueResponse
        ]);
    }

    //Plan de internet

    public function createPPPProfile(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);

        // Definir los parámetros del plan (PPP Profile)
        $params = [
            'name' => 'Plan Basico',          // Nombre del perfil (plan)
            'rate-limit' => '100M/100M',      // Límite de velocidad (download/upload)
            'local-address' => '192.168.1.1', // Dirección IP del router o gateway
            'dns-server' => '8.8.8.8',        // Servidor DNS (opcional)
            'comment' => 'Plan 100M para clientes básicos' // Descripción del plan
        ];

        // Ejecutar el comando para añadir el perfil PPP
        $response = $router->execute('/ppp/profile/add', $params);

        return response(["message" => $response]);
    }

    //Login o perfil del usuario
    public function createPPPSecret(Request $request)
    {
        $config = HelperMikrotikData::getConfig();
        $router = new MikrotikApi($config);

        // Definir los parámetros del secreto (PPP Secret)
        $params = [
            'name' => 'cliente1',              // Nombre del usuario
            'password' => 'password123',       // Contraseña del usuario
            'service' => 'pppoe',              // Tipo de servicio (pppoe)
            'profile' => 'Plan Basico',        // El perfil (PPP Profile) que creamos anteriormente
            'remote-address' => '192.168.1.50', // Dirección IP (si es estática, opcional)
            'comment' => 'Cliente 1 con plan basico' // Descripción opcional
        ];

        // Ejecutar el comando para añadir el secreto PPP
        $response = $router->execute('/ppp/secret/add', $params);

        return response(["message" => $response]);
    }


}
