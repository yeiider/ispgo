# Integración IPTV XUI.one - Guía de API GraphQL

Esta documentación explica los tipos, consultas y mutaciones de GraphQL diseñadas para interactuar con la integración de IPTV XUI.one en ISPGO.

---

## 1. Configuración del Sistema

Las opciones de integración se definen bajo la sección `iptv` en los ajustes generales del sistema (`config/settings.php`):

- **General Information (`iptv/general/`)**:
  - `enabled`: Habilita o deshabilita la integración de IPTV (boolean).
  - `url`: URL del servidor de administración de XUI.one (ej. `http://domain_or_ip:port/`).
  - `access_code`: Código de acceso único del API.
  - `api_key`: Clave secreta del API para autorizar todas las peticiones.
- **Activation Defaults (`iptv/activation/`)**:
  - `default_max_connections`: Número por defecto de conexiones concurrentes permitidas para la línea (ej. `1`).
  - `default_member_id`: ID del reseller o administrador dueño de la línea en XUI.one.
  - `default_bouquets`: Lista separada por comas de IDs de Bouquets (paquetes) asignados por defecto.

---

## 2. Tipos GraphQL

### `IptvLineUser`
Representa los datos locales de la línea de IPTV vinculada a un servicio de cliente.

```graphql
type IptvLineUser {
    id: ID!
    service_id: ID!
    line_id: Int
    username: String!
    password: String!
    max_connections: Int!
    expire_date: DateTime
    bouquets: [String!]
    status: String!          # active, disabled, banned
    created_at: DateTime!
    updated_at: DateTime!
    service: Service
}
```

### `IptvBouquet`
Estructura de datos para los Bouquets del catálogo en XUI.one.

```graphql
type IptvBouquet {
    id: ID!
    name: String!
}
```

### `IptvPackage`
Estructura de datos para los Packages del catálogo en XUI.one.

```graphql
type IptvPackage {
    id: ID!
    name: String!
}
```

---

## 3. Consultas (Queries)

### Obtener detalles de una línea local
`iptvLineUser(id: ID!): IptvLineUser`
Retorna la línea IPTV guardada localmente por su ID de base de datos.

### Listar líneas de IPTV locales
`iptvLineUsers(service_id: ID): [IptvLineUser!]!`
Lista todos los usuarios de línea locales, filtrando opcionalmente por `service_id`.

### Obtener el catálogo de Bouquets de XUI.one
`iptvBouquets: [IptvBouquet!]!`
Consulta la lista maestra de Bouquets directamente de la API de XUI.one para usar en selectores del frontend.

### Obtener el catálogo de Packages de XUI.one
`iptvPackages: [IptvPackage!]!`
Consulta la lista maestra de Packages de canales directamente de la API de XUI.one.

### Consultar listado completo de líneas directamente desde XUI.one
`iptvLinesFromApi: JSON`
Retorna la respuesta JSON cruda del catálogo completo de líneas registradas en XUI.one.

### Consultar líneas de IPTV desde el Cliente (Customer)
Dado que un cliente tiene múltiples servicios y cada servicio puede enlazarse a una línea IPTV, se expone la relación `iptvLineUsers` directamente en el tipo `Customer`. Esto permite consultar los usuarios de IPTV asociados a un cliente de forma directa:

```graphql
query {
  customer(id: 1) {
    id
    first_name
    last_name
    iptvLineUsers {
      id
      line_id
      username
      status
      expire_date
    }
  }
}
```

---

## 4. Mutaciones (Mutations)

### Crear una Línea de Usuario
`createIptvLineUser`
Envía la petición de registro a XUI.one y guarda localmente la línea asociada a un servicio.

```graphql
mutation CreateIptvLineUser(
  $service_id: ID!
  $username: String!
  $password: String!
  $max_connections: Int
  $expire_date: String
  $bouquets: [String!]!
) {
  createIptvLineUser(
    service_id: $service_id
    username: $username
    password: $password
    max_connections: $max_connections
    expire_date: $expire_date
    bouquets: $bouquets
  ) {
    id
    line_id
    username
    status
    max_connections
    expire_date
    bouquets
  }
}
```

### Actualizar una Línea
`updateIptvLineUser`
Modifica los parámetros de la línea (como contraseña, conexiones, vencimiento y bouquets) en XUI.one y actualiza la BD local.

```graphql
mutation UpdateIptvLineUser(
  $id: ID!
  $password: String
  $max_connections: Int
  $expire_date: String
  $bouquets: [String!]
) {
  updateIptvLineUser(
    id: $id
    password: $password
    max_connections: $max_connections
    expire_date: $expire_date
    bouquets: $bouquets
  ) {
    id
    username
    max_connections
    expire_date
    bouquets
  }
}
```

### Eliminar una Línea
`deleteIptvLineUser`
Elimina definitivamente la línea de la base de datos de XUI.one y de la base de datos local de ISPGO.

```graphql
mutation DeleteIptvLineUser($id: ID!) {
  deleteIptvLineUser(id: $id) {
    success
    message
  }
}
```

### Control Directo de Estados
Para inhabilitar, reactivar o restringir manualmente una cuenta sin borrarla:

- **Deshabilitar Línea** (Corte de cartera):
  `disableIptvLineUser(id: ID!): ActionResult`
- **Habilitar Línea** (Reconexión):
  `enableIptvLineUser(id: ID!): ActionResult`
- **Bannear Línea** (Infracción de políticas/abuso):
  `banIptvLineUser(id: ID!): ActionResult`
- **Remover Ban**:
  `unbanIptvLineUser(id: ID!): ActionResult`

```graphql
mutation DisableLine($id: ID!) {
  disableIptvLineUser(id: $id) {
    success
    message
  }
}
```

### Sincronizar Estado de XUI.one al Backend
`syncIptvLineUserFromApi(id: ID!): IptvLineUser`
Consulta el estado en tiempo real de la línea en XUI.one (fecha de expiración, conexiones activas, si está banneado, etc.) y actualiza la información en la base de datos de ISPGO.

---

## 5. Orquestación y Automatización

Para agilizar los procesos de facturación, la integración se conecta al ciclo de vida del servicio de internet de ISPGO:

- **Suspensión Automática**: Cuando un servicio cambia de estado a `suspended` (ej. corte de cartera por facturas impagas), el listener `ServiceIptvManagerListener` se ejecuta de forma asíncrona enviando una petición de inhabilitación a XUI.one (`disable_line`), rechazando transmisiones activas de manera inmediata.
- **Reconexión Automática**: Una vez conciliado el pago en ISPGO y restaurado el servicio a estado `active`, se envía automáticamente la petición de habilitación (`enable_line`) a XUI.one para restablecer el servicio de streaming sin intervención manual.
