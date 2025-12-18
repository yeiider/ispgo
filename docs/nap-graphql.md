### NAP GraphQL API

#### Resumen
APIs GraphQL para gestionar cajas NAP (NapBox) y puertos (NapPort). Permite mapear la red FTTH de una ISP, asignar servicios a puertos, relacionar cajas con routers y registrar el color del hilo de fibra.

#### Modelos y relaciones
- NapBox
  - Campos clave: `name`, `code`, `address`, `latitude`, `longitude`, `status`, `capacity`, `technology_type`, `installation_date`, `brand`, `model`, `distribution_order`, `parent_nap_id`, `router_id`, `fiber_color`
  - Relaciones: `ports` (NapPort[]), `router` (Router)
- NapPort
  - Campos clave: `nap_box_id`, `port_number`, `port_name`, `status` (available|occupied|damaged|maintenance|reserved|testing), `connection_type` (fiber|coaxial|ethernet|mixed), `service_id`, `code`, `color`, `last_signal_check`, `signal_strength`, `port_config`, `technician_notes`, `last_maintenance`, `warranty_until`
  - Relaciones: `service` (Service), `napBox` (NapBox)
- Service
  - Relaciones añadidas: `napPort()` (hasOne), `napBox()` (hasOneThrough NapPort)
- Router
  - Relación añadida: `napBoxes()` (hasMany)

#### Esquema
Archivo: `graphql/nap.graphql`

Tipos expuestos: `NapBox`, `NapPort`

Queries principales:
```graphql
query ($router_id: ID) {
  napBoxes(router_id: $router_id) {
    id name code router_id fiber_color capacity status
    ports { id port_number status service_id color code service { id customer_id service_status sn } }
    services { id customer_id service_status sn }
  }
}

query ($id: ID!) {
  napBox(id: $id) {
    id name code
    ports { id port_number status color code service { id customer_id service_status sn } }
    services { id customer_id service_status sn }
  }
}

query ($nap_box_id: ID!) { napPorts(nap_box_id: $nap_box_id) { id port_number status service_id } }

query ($nap_box_id: ID!) { availableNapPorts(nap_box_id: $nap_box_id) { id port_number } }

# Lista directa de servicios de una caja (útil para la vista tipo araña)
query ($nap_box_id: ID!) {
  napBoxServices(nap_box_id: $nap_box_id) { id customer_id service_status sn }
}
```

Mutations principales:
```graphql
mutation CreateNapBox($input: CreateNapBoxInput!) {
  createNapBox(input: $input) { id name code router_id fiber_color }
}

mutation UpdateNapBox($id: ID!, $input: UpdateNapBoxInput!) {
  updateNapBox(id: $id, input: $input) { id name code router_id fiber_color }
}

mutation CreateNapPort($input: CreateNapPortInput!) {
  createNapPort(input: $input) { id nap_box_id port_number status }
}

mutation UpdateNapPort($id: ID!, $input: UpdateNapPortInput!) {
  updateNapPort(id: $id, input: $input) { id port_number status service_id color code }
}

mutation AssignService($service_id: ID!, $nap_port_id: ID!) {
  assignServiceToNapPort(service_id: $service_id, nap_port_id: $nap_port_id) { id port_number status service_id }
}

mutation ReleasePort($nap_port_id: ID!) {
  releaseNapPort(nap_port_id: $nap_port_id) { id port_number status service_id }
}

mutation AssignRouter($nap_box_id: ID!, $router_id: ID!) {
  assignRouterToNapBox(nap_box_id: $nap_box_id, router_id: $router_id) { id name router_id }
}
```

Ejemplos de variables:
```json
// Crear NapBox
{
  "input": {
    "name": "NAP-001",
    "code": "NBX-001",
    "address": "Calle 123",
    "latitude": 4.710989,
    "longitude": -74.072092,
    "status": "active",
    "capacity": 16,
    "technology_type": "fiber",
    "installation_date": "2025-12-01",
    "router_id": 2,
    "fiber_color": "azul"
  }
}

// Crear NapPort
{
  "input": {
    "nap_box_id": 1,
    "port_number": 1,
    "connection_type": "fiber",
    "code": "NBX-001-P01",
    "color": "azul"
  }
}

// Asignar servicio al puerto
{ "service_id": 123, "nap_port_id": 10 }
```

#### Notas de implementación
- Se añadieron migraciones para `nap_boxes` (columnas `router_id`, `fiber_color`) y para `nap_ports` (columnas `code`, `color`).
- Las relaciones Eloquent fueron actualizadas y las mutaciones validan la unicidad del `port_number` por `nap_box_id` y el estado del puerto al asignar un servicio.
- Para actualizar el color del hilo de un puerto, use `updateNapPort` con `color`; para la caja use `updateNapBox` con `fiber_color`.
- Para la vista tipo araña: cada `NapBox` expone `ports { ... service { ... } }` y además un atajo `services` que devuelve todos los servicios conectados a la caja.

#### Requisitos previos
Ejecutar migraciones:
```bash
php artisan migrate
```

#### Breve explicación del servicio
Este servicio permite mapear las cajas NAP de la ISP, administrar sus puertos y asignar servicios a puertos específicos. Cada NAP puede asociarse a un router, y cada puerto puede registrar un color de hilo, código identificador y estado operacional. Las operaciones se exponen mediante GraphQL para integrar fácilmente aplicaciones de gestión, inventario y despliegue de red.
