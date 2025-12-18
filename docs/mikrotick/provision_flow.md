# Flujo de Provisionamiento de Mikrotik

Este documento describe el nuevo flujo simplificado para amarrar una IP a una dirección MAC y asignar un Simple Queue para controlar el ancho de banda.

## Arquitectura

```
┌─────────────┐     GraphQL      ┌──────────────────┐     HTTP      ┌─────────────────┐
│   Cliente   │ ─────────────▶   │   ISPGO Backend  │ ───────────▶  │  Microservicio  │
│  (Frontend) │                  │   (Laravel)      │               │   Mikrotik API  │
└─────────────┘                  └──────────────────┘               └─────────────────┘
                                         │                                   │
                                         │                                   │
                                         ▼                                   ▼
                                 ┌──────────────────┐               ┌─────────────────┐
                                 │   Base de Datos  │               │  Router Mikrotik│
                                 │   (Servicios)    │               │                 │
                                 └──────────────────┘               └─────────────────┘
```

## Flujo de Provisión Manual

El flujo de provisión ya **NO es automático** al crear un servicio. Ahora se realiza manualmente:

### Paso 1: Crear el Servicio
El servicio se crea sin IP asignada o con una IP temporal.

### Paso 2: Consultar DHCP Leases
```graphql
query ObtenerLeases {
  mikrotikDhcpLeases(router_id: "1") {
    success
    count
    leases {
      address
      mac_address
      host_name
      status
    }
  }
}
```

### Paso 3: Seleccionar IP y MAC
El usuario selecciona una IP disponible del listado de DHCP leases junto con su MAC address.

### Paso 4: Provisionar el Servicio
```graphql
mutation ProvisionarServicio {
  mikrotikProvisionService(input: {
    service_id: "123"
    ip_address: "192.168.88.100"
    mac_address: "AA:BB:CC:DD:EE:FF"
  }) {
    success
    message
    ip
    mac
    queue_name
    max_limit
  }
}
```

Esta operación:
1. **Amarra la IP a la MAC** en el servidor DHCP del router
2. **Crea un Simple Queue** con las velocidades del plan
3. **Actualiza el servicio** en la base de datos con la IP y MAC

## Operaciones Disponibles

### Consultas (Queries)

| Query | Descripción |
|-------|-------------|
| `mikrotikDhcpLeases` | Lista todos los DHCP leases del router |
| `mikrotikFindLeaseByMac` | Busca un lease por MAC address |
| `mikrotikFindLeaseByIp` | Busca un lease por IP |
| `mikrotikSimpleQueues` | Lista todos los Simple Queues |
| `mikrotikFindQueue` | Busca un queue por nombre |
| `mikrotikSystemResources` | Obtiene estado del router (CPU, memoria) |
| `mikrotikConfigStatus` | Obtiene configuración del módulo |
| `mikrotikServiceQueue` | Obtiene el queue de un servicio específico |

### Mutaciones (Mutations)

| Mutation | Descripción |
|----------|-------------|
| `mikrotikBindIp` | Amarra IP a MAC (solo DHCP binding) |
| `mikrotikCreateQueue` | Crea Simple Queue (solo queue) |
| `mikrotikProvisionService` | Provisión completa (binding + queue) |
| `mikrotikSuspendService` | Suspende servicio (deshabilita/limita queue) |
| `mikrotikActivateService` | Activa servicio (habilita queue) |
| `mikrotikUpdateSpeed` | Actualiza velocidad (cuando cambia de plan) |
| `mikrotikDeprovisionService` | Elimina configuración del router |
| `mikrotikEnableQueue` | Habilita un queue específico |
| `mikrotikDisableQueue` | Deshabilita un queue específico |
| `mikrotikDeleteQueue` | Elimina un queue específico |

## Nomenclatura de Queues

Los queues se nombran usando el **ID del servicio** con un prefijo configurable:

- Prefijo por defecto: `""` (vacío)
- Ejemplo con prefijo `SVC_`: `SVC_123`
- Ejemplo sin prefijo: `123`

Esto permite identificar fácilmente los queues y su servicio asociado.

## Velocidades

Las velocidades se obtienen del **Plan** asociado al servicio:

| Campo del Plan | Uso |
|----------------|-----|
| `upload_speed` | Velocidad de subida (Mbps) |
| `download_speed` | Velocidad de bajada (Mbps) |

El formato enviado al router es: `{upload}M/{download}M`

Ejemplo: Un plan con 10 Mbps upload y 50 Mbps download genera: `10M/50M`

## Configuración

La configuración se almacena por **router_id** (scope_id), permitiendo diferentes configuraciones para cada router:

### Configuración General
- `mikrotik/general/enabled`: Habilitar módulo
- `mikrotik/general/api_base_url`: URL del microservicio
- `mikrotik/general/api_timeout`: Timeout de peticiones

### Conexión al Router
- `mikrotik/router_connection/host`: IP del router
- `mikrotik/router_connection/port`: Puerto API (8728)
- `mikrotik/router_connection/username`: Usuario
- `mikrotik/router_connection/password`: Contraseña
- `mikrotik/router_connection/use_ssl`: Usar SSL

### DHCP
- `mikrotik/dhcp/dhcp_enabled`: Habilitar binding
- `mikrotik/dhcp/dhcp_server`: Nombre del servidor DHCP

### Simple Queue
- `mikrotik/simple_queue/queue_enabled`: Habilitar queues
- `mikrotik/simple_queue/queue_name_prefix`: Prefijo de nombres

### Acciones de Servicio
- `mikrotik/service_actions/suspend_action`: Acción al suspender
- `mikrotik/service_actions/activate_action`: Acción al activar

## Ejemplo Completo de Integración

```javascript
// 1. Obtener leases disponibles
const { data: leasesData } = await graphqlClient.query({
  query: MIKROTIK_DHCP_LEASES,
  variables: { router_id: service.router_id }
});

// 2. Usuario selecciona IP y MAC del listado
const selectedLease = leasesData.mikrotikDhcpLeases.leases[0];

// 3. Provisionar servicio
const { data: provisionData } = await graphqlClient.mutate({
  mutation: MIKROTIK_PROVISION_SERVICE,
  variables: {
    input: {
      service_id: service.id,
      ip_address: selectedLease.address,
      mac_address: selectedLease.mac_address
    }
  }
});

if (provisionData.mikrotikProvisionService.success) {
  console.log('Servicio provisionado:', provisionData.mikrotikProvisionService);
}
```
