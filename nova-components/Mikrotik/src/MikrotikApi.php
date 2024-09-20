<?php

namespace Ispgo\Mikrotik;

use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Query;
use Exception;

class MikrotikApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Constructor que inicializa el cliente RouterOS usando las configuraciones de MikroTik.
     *
     * @param array $config Configuración del cliente (host, user, pass, etc.)
     * @param Client|null $client Opcional: Cliente inyectado para pruebas
     * @throws Exception Si no se puede conectar a MikroTik.
     */
    public function __construct(array $config, Client $client = null)
    {
        // Si un cliente se pasa como parámetro, lo usamos. De lo contrario, creamos uno nuevo.
        if ($client) {
            $this->client = $client;
        } else {
            try {
                $this->client = new Client($config);
            } catch (Exception $e) {
                throw new Exception('Error connecting to MikroTik: ' . $e->getMessage());
            }
        }
    }

    /**
     * Ejecutar una consulta de tipo "print" con condiciones opcionales.
     *
     * @param string $command Comando a ejecutar (por ejemplo, '/ip/address/print')
     * @param array|null $conditions Condiciones opcionales para el "where"
     * @param string|null $operator Operador para combinar condiciones (por defecto 'and')
     * @return array|null Respuesta de la consulta
     * @throws Exception Si ocurre un error durante la consulta
     */
    public function get(string $command, array $conditions = null, string $operator = null): ?array
    {
        try {
            $query = new Query($command);

            // Agregar condiciones opcionales
            if ($conditions) {
                foreach ($conditions as $condition) {
                    if (is_array($condition) && count($condition) >= 2) {
                        $query->where($condition[0], $condition[1]);
                    } else {
                        throw new Exception('Invalid condition format.');
                    }
                }
            }

            // Aplicar el operador lógico si está definido
            if ($operator) {
                $query->operations($operator);
            }

            // Ejecutar la consulta
            return $this->client->query($query)->read();
        } catch (Exception $e) {
            throw new Exception('Error executing get query: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar una operación "add", "update" o "delete".
     *
     * @param string $command Comando a ejecutar (por ejemplo, '/ip/hotspot/ip-binding/add')
     * @param array $params Parámetros de la operación
     * @return array|null Respuesta de la consulta
     * @throws Exception Si ocurre un error durante la consulta
     */
    public function execute(string $command, array $params): ?array
    {
        try {
            $query = new Query($command);

            // Añadir los parámetros al query
            foreach ($params as $key => $value) {
                $query->equal($key, $value);
            }
            // Ejecutar la consulta
            return $this->client->query($query)->read();
        } catch (Exception $e) {
            throw new Exception('Error executing command: ' . $e->getMessage());
        }
    }

    /**
     * Exportar todas las configuraciones del router MikroTik.
     *
     * @return string|null Respuesta del comando de exportación
     * @throws Exception Si ocurre un error durante la exportación
     */
    public function exportConfig(): ?string
    {
        try {
            // Ejecutar el comando de exportación
            return $this->client->query('/export')->read();
        } catch (Exception $e) {
            throw new Exception('Error exporting configuration: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar una consulta con múltiples condiciones y operadores.
     *
     * @param string $command Comando a ejecutar (por ejemplo, '/interface/print')
     * @param array $conditions Condiciones "where" (por ejemplo, [['type', 'ether'], ['type', 'vlan']])
     * @param string|null $operator Operador lógico (por ejemplo, '|')
     * @return array|null Respuesta de la consulta
     * @throws Exception Si ocurre un error durante la consulta
     */
    public function queryWithConditions(string $command, array $conditions, string $operator = null): ?array
    {
        return $this->get($command, $conditions, $operator);
    }

    /**
     * Ejecutar un comando con etiquetas (tags).
     *
     * @param string $command El comando a ejecutar (por ejemplo, '/interface/set')
     * @param array $conditions Condiciones "where" (por ejemplo, ['.id' => 'ether1'])
     * @param int $tag Etiqueta a usar
     * @return array|null Respuesta de la consulta
     * @throws Exception Si ocurre un error durante la consulta
     */
    public function queryWithTag(string $command, array $conditions, int $tag): ?array
    {
        try {
            $query = new Query($command);

            // Añadir las condiciones al query
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }

            // Añadir la etiqueta
            $query->tag($tag);

            // Ejecutar la consulta
            return $this->client->query($query)->read();
        } catch (Exception $e) {
            throw new Exception('Error in queryWithTag: ' . $e->getMessage());
        }
    }

    /**
     * Buscar un cliente PPPoE por nombre de usuario y obtener su ID.
     *
     * @param string $username Nombre de usuario PPPoE.
     * @return string|null El ID del cliente PPPoE, o null si no se encuentra.
     * @throws Exception
     */
    public function findPPPoEClientIdByUsername(string $username,string $command): ?string
    {
        // Utiliza el método get() de MikrotikApi para buscar el cliente por nombre de usuario
        $clients = $this->get($command, [['name', $username]]);

        if (is_array($clients) && count($clients) > 0) {
            return $clients[0]['.id'] ?? null;  // Devolver el ID del cliente si se encuentra
        }

        return null;  // Devolver null si no se encuentra el cliente
    }

    /**
     * Cierra la conexión a MikroTik.
     */
    public function disconnect()
    {
        $this->client = null;
    }

    /**
     * Destructor para asegurarse de que la conexión se cierra.
     */
    public function __destruct()
    {
        $this->disconnect();
    }


}

