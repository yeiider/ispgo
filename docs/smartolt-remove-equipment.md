# SmartOLT - Caso de Uso: Retirar Equipo ONU

## Descripción General

Este documento describe el caso de uso para retirar un equipo ONU (Optical Network Unit) de un servicio de cliente en el sistema ISPGO integrado con SmartOLT.

## Objetivo

Permitir a los administradores y técnicos retirar un equipo ONU asignado a un servicio de cliente, eliminando el número de serie (SN) del servicio para indicar que el usuario ya no tiene equipo asignado.

## Casos de Uso

### 1. Retiro de Equipo por Baja de Servicio
- El cliente cancela su servicio de internet
- El técnico debe retirar el equipo ONU instalado
- Se requiere desasociar el SN del servicio en el sistema

### 2. Reemplazo de Equipo
- El equipo ONU presenta fallas
- Se requiere reemplazar por un nuevo equipo
- Primero se retira el equipo antiguo (se elimina el SN)
- Luego se asigna el nuevo equipo con su nuevo SN

### 3. Cambio de Dirección
- El cliente se muda a una nueva ubicación
- Se retira el equipo de la ubicación anterior
- Se puede reasignar el mismo equipo o uno nuevo en la nueva ubicación

## Flujo de Proceso

### Paso 1: Identificación del Servicio
El sistema identifica el servicio asociado al número de serie (SN) del equipo ONU que se va a retirar.

```graphql
query {
  smartOltOnuDetails(sn: "ZTEGD38EC236") {
    sn
    name
    address
    status
    onu_type_name
  }
}
```

### Paso 2: Validación
- Verificar que el servicio existe en el sistema
- Confirmar que el SN corresponde al servicio correcto
- Validar permisos del usuario que ejecuta la acción

### Paso 3: Ejecución del Retiro
Se ejecuta la mutación para eliminar el SN del servicio:

```graphql
mutation {
  smartOltRemoveEquipment(sn: "ZTEGD38EC236") {
    success
    message
  }
}
```

### Paso 4: Resultado
El sistema:
- Localiza el servicio con el SN especificado
- Elimina la ONU del sistema SmartOLT usando el API `/api/onu/delete/{external_id}`
- Si la eliminación de SmartOLT falla, se registra en logs pero continúa el proceso
- Establece el campo `sn` a `null` en la tabla de servicios
- Guarda los cambios
- Retorna confirmación de éxito o error

## Modelo de Datos

### Tabla: services

Campo afectado:
- `sn` (VARCHAR): Se establece en NULL al retirar el equipo

```php
// Antes del retiro
sn: "ZTEGD38EC236"

// Después del retiro
sn: null
```

## Implementación Técnica

### Archivo: app/GraphQL/Mutations/SmartOltMutation.php

```php
public function smartOltRemoveEquipment($root, array $args)
{
    try {
        // Buscar el servicio por SN
        $service = \App\Models\Services\Service::where('sn', $args['sn'])->first();

        if (!$service) {
            return [
                'success' => false,
                'message' => 'Service with SN ' . $args['sn'] . ' not found'
            ];
        }

        // Obtener el external_id (que es el mismo SN)
        $externalId = $args['sn'];

        // Eliminar la ONU del SmartOLT
        try {
            $response = $this->apiManager->deleteOnuByExternalId($externalId);
            $data = $response->json();

            if ($data['status'] !== true) {
                Log::warning('Failed to delete ONU from SmartOLT', [
                    'sn' => $args['sn'],
                    'error' => $data['error'] ?? 'Unknown error'
                ]);
                // Continuar con la eliminación del SN aunque falle en SmartOLT
            }
        } catch (\Exception $e) {
            Log::error('Error deleting ONU from SmartOLT', [
                'sn' => $args['sn'],
                'error' => $e->getMessage()
            ]);
            // Continuar con la eliminación del SN aunque falle en SmartOLT
        }

        // Remover el SN del servicio
        $service->sn = null;
        $service->save();

        return [
            'success' => true,
            'message' => 'Equipment removed successfully from service and SmartOLT'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
```

### Archivo: nova-components/Smartolt/src/Services/ApiManager.php

```php
/**
 * Eliminar ONU del SmartOLT por external_id.
 *
 * @param string $externalId
 * @return Response
 * @throws \Exception
 */
public function deleteOnuByExternalId(string $externalId): Response
{
    $this->validateExternalId($externalId);
    return $this->request('api/onu/delete/' . $externalId, [], false, 'post');
}
```

## Respuestas de la API

### Éxito
```json
{
  "success": true,
  "message": "Equipment removed successfully from service and SmartOLT"
}
```

### Error - Servicio no encontrado
```json
{
  "success": false,
  "message": "Service with SN ZTEGD38EC236 not found"
}
```

### Error - Excepción
```json
{
  "success": false,
  "message": "Database connection error"
}
```

## Consideraciones Importantes

### Seguridad
- Solo usuarios autenticados con permisos adecuados pueden ejecutar esta acción
- Se aplican los filtros de scope globales del modelo Service basados en router_id
- Se registra la acción en los logs del sistema

### Integridad de Datos
- La ONU se elimina completamente del sistema SmartOLT mediante el endpoint `/api/onu/delete/{external_id}`
- Al establecer `sn` en NULL, el servicio permanece activo pero sin equipo asignado
- Si la eliminación en SmartOLT falla, el proceso continúa y se registra en logs (garantiza que el SN se elimine del servicio)
- El estado del servicio (`service_status`) NO se modifica automáticamente
- El cliente y la configuración del plan se mantienen intactos

### Acciones Relacionadas
Después de retirar el equipo, considere:
1. Cambiar el estado del servicio si corresponde (suspended, cancelled)
2. Generar una orden de trabajo para el retiro físico del equipo
3. Actualizar el inventario de equipos disponibles
4. Notificar al cliente sobre el retiro del equipo

## Otras Mutaciones Relacionadas

### Habilitar ONU
```graphql
mutation {
  smartOltEnableOnu(sn: "ZTEGD38EC236") {
    success
    message
  }
}
```

### Deshabilitar ONU
```graphql
mutation {
  smartOltDisableOnu(sn: "ZTEGD38EC236") {
    success
    message
  }
}
```

### Reiniciar ONU
```graphql
mutation {
  smartOltRebootOnu(external_id: "ZTEGD38EC236") {
    success
    message
  }
}
```

## Queries Relacionadas

### Obtener Detalles del ONU
```graphql
query {
  smartOltOnuDetails(sn: "ZTEGD38EC236") {
    unique_external_id
    sn
    olt_name
    onu_type_name
    name
    address
    contact
    status
    signal
    signal_1310
    signal_1490
    service_ports {
      service_port
      vlan
      upload_speed
      download_speed
    }
    ethernet_ports {
      port
      admin_state
      mode
    }
    wifi_ports {
      port
      admin_state
      ssid
    }
  }
}
```

### Obtener Imagen del Tipo de ONU
```graphql
query {
  smartOltOnuTypeImage(onu_type_id: 105) {
    image_base64
    content_type
  }
}
```

## Ejemplo de Flujo Completo

1. **Consultar detalles del equipo antes del retiro**
```graphql
query {
  smartOltOnuDetails(sn: "ZTEGD38EC236") {
    sn
    status
    name
    address
  }
}
```

2. **Deshabilitar el equipo (opcional)**
```graphql
mutation {
  smartOltDisableOnu(sn: "ZTEGD38EC236") {
    success
    message
  }
}
```

3. **Retirar el equipo del servicio**
```graphql
mutation {
  smartOltRemoveEquipment(sn: "ZTEGD38EC236") {
    success
    message
  }
}
```

4. **Verificar que el SN fue removido**
```sql
SELECT id, customer_id, sn, service_status
FROM services
WHERE sn IS NULL AND customer_id = [customer_id];
```

## Conclusión

La funcionalidad de retiro de equipo ONU proporciona una manera limpia y controlada de desasociar equipos de servicios de clientes, manteniendo la integridad de los datos y permitiendo una gestión eficiente del inventario de equipos en la red.
