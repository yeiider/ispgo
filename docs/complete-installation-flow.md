# Flujo para Completar una Instalación desde la App Móvil

## Resumen

El técnico llega al domicilio del cliente, conecta la ONU y desde la app completa la instalación. El flujo consta de **2 pasos**: primero obtener los datos del formulario, luego enviar la confirmación.

---

## Paso 1 — Obtener datos del formulario

Antes de mostrar el formulario al técnico, la app consulta todos los datos necesarios en **una sola llamada** usando el número de serie (SN) de la ONU.

### Query GraphQL

```graphql
query GetInstallationFormData($sn: String!) {
  smartOltInstallationFormData(sn: $sn) {
    onu {
      olt_id
      pon_type
      board
      port
      onu
      sn
      onu_type_name
      onu_type_id
    }
    vlans {
      id
      vlan
      description
      scope
    }
    zones {
      id
      name
    }
    speed_profiles {
      id
      name
      speed
      direction
    }
  }
}
```

### Variables

```json
{
  "sn": "HWTC48B1C9B2"
}
```

### Lo que hace el backend

1. Llama `GET /api/onu/unconfigured_onus?sn=HWTC48B1C9B2` en SmartOLT y extrae los datos de la ONU (board, port, pon_type, olt_id, etc.)
2. Con el `olt_id` obtenido, llama `GET /api/olt/get_vlans/{olt_id}` para traer las VLANs disponibles
3. Llama `GET /api/system/get_zones` para traer las zonas
4. Llama `GET /api/system/get_speed_profiles` para traer los perfiles de velocidad (planes)

### Respuesta de ejemplo

```json
{
  "data": {
    "smartOltInstallationFormData": {
      "onu": {
        "olt_id": "22",
        "pon_type": "gpon",
        "board": "1",
        "port": "15",
        "onu": "1",
        "sn": "HWTC48B1C9B2",
        "onu_type_name": "EG8041V5",
        "onu_type_id": "114"
      },
      "vlans": [
        { "id": "2398", "vlan": "100", "description": null, "scope": "internet" }
      ],
      "zones": [
        { "id": "1", "name": "Zona Norte" }
      ],
      "speed_profiles": [
        { "id": "5", "name": "10MB", "speed": "10240", "direction": "both" }
      ]
    }
  }
}
```

---

## Paso 2 — Completar la instalación

El técnico selecciona la VLAN, la zona y el perfil de velocidad. La app envía la mutación con todos los datos.

> Los campos `olt_id`, `pon_type`, `board`, `port` y `onu_type` los toma la app directamente del `onu` devuelto en el Paso 1, el técnico no los escribe manualmente.

### Mutation GraphQL

```graphql
mutation CompleteInstallation(
  $ticket_id: ID!
  $olt_id: Int!
  $pon_type: String!
  $board: Int!
  $port: Int!
  $sn: String!
  $vlan: Int!
  $onu_type: String!
  $zone: Int!
  $onu_mode: String!
  $odb: String
  $resolution_notes: String
) {
  completeInstallation(
    ticket_id: $ticket_id
    olt_id: $olt_id
    pon_type: $pon_type
    board: $board
    port: $port
    sn: $sn
    vlan: $vlan
    onu_type: $onu_type
    zone: $zone
    onu_mode: $onu_mode
    odb: $odb
    resolution_notes: $resolution_notes
  ) {
    success
    message
  }
}
```

### Variables de ejemplo

```json
{
  "ticket_id": "42",
  "olt_id": 22,
  "pon_type": "gpon",
  "board": 1,
  "port": 15,
  "sn": "HWTC48B1C9B2",
  "vlan": 100,
  "onu_type": "114",
  "zone": 1,
  "onu_mode": "router",
  "resolution_notes": "Instalación completada sin novedades."
}
```

### Lo que hace el backend

1. Valida que el ticket exista, sea de tipo `installation` y tenga servicio y cliente asociados
2. Llama `POST /api/onu/authorize_onu` en SmartOLT para autorizar la ONU
3. Guarda el SN en el servicio y activa el estado a `active`
4. Encola `CompleteOnuActivationJob` (30 segundos) → configura mgmt IP DHCP + TR069 + modo WAN
5. Encola `ProcessOnuAuthorization` (4 minutos) → provisionamiento en Mikrotik
6. Cierra el ticket con estado `closed`

### Respuesta esperada

```json
{
  "data": {
    "completeInstallation": {
      "success": true,
      "message": "Instalación completada. La ONU se está configurando en segundo plano."
    }
  }
}
```

---

## Query opcional — VLANs por OLT

Si la app necesita refrescar solo la lista de VLANs (sin rehacer todo el paso 1):

```graphql
query GetVlans($olt_id: ID!) {
  smartOltVlans(olt_id: $olt_id) {
    id
    vlan
    description
    scope
  }
}
```

---

## Diagrama de flujo

```
App móvil                          Backend (GraphQL)              SmartOLT API
    |                                      |                            |
    |-- smartOltInstallationFormData(sn) ->|                            |
    |                                      |-- GET unconfigured_onus?sn |
    |                                      |<-- { onu data + olt_id } --|
    |                                      |-- GET get_vlans/{olt_id} --|
    |                                      |<-- [ vlans ] --------------|
    |                                      |-- GET get_zones ---------->|
    |                                      |-- GET get_speed_profiles ->|
    |<-- { onu, vlans, zones, profiles } --|                            |
    |                                      |                            |
    | [técnico selecciona vlan, zona, plan]|                            |
    |                                      |                            |
    |-- completeInstallation(args) ------->|                            |
    |                                      |-- POST authorize_onu ----->|
    |                                      |<-- { status: true } -------|
    |                                      |-- [encola jobs async]      |
    |<-- { success: true } ---------------|                            |
```

---

## Campos que completa el técnico en el formulario

| Campo         | Origen                          | Selecciona el técnico |
|---------------|---------------------------------|-----------------------|
| `olt_id`      | Respuesta ONU (Paso 1)          | No                    |
| `pon_type`    | Respuesta ONU (Paso 1)          | No                    |
| `board`       | Respuesta ONU (Paso 1)          | No                    |
| `port`        | Respuesta ONU (Paso 1)          | No                    |
| `onu_type`    | Respuesta ONU — `onu_type_id`   | No                    |
| `sn`          | Escaneado / ingresado           | Sí                    |
| `vlan`        | Lista de VLANs (Paso 1)         | Sí                    |
| `zone`        | Lista de zonas (Paso 1)         | Sí                    |
| `onu_mode`    | Fijo: `router`                  | No (o configurable)   |
| `odb`         | Opcional                        | Sí (opcional)         |
| `resolution_notes` | Texto libre                | Sí (opcional)         |
