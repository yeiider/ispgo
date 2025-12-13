# Módulo Mikrotik - ISPGO

## Descripción

Este módulo permite la integración con routers Mikrotik a través de un microservicio externo. Proporciona funcionalidades para:

- **DHCP Binding**: Amarrar IPs a direcciones MAC
- **Simple Queue**: Control de ancho de banda por servicio
- **Monitoreo**: Consultar estado del router

## Arquitectura Simplificada

El módulo utiliza una arquitectura de microservicio:

1. **ISPGO (Laravel)**: Expone API GraphQL para el frontend
2. **Microservicio Mikrotik**: Maneja la comunicación directa con los routers
3. **Routers Mikrotik**: Equipos de red que ejecutan RouterOS

## Instalación

El módulo está incluido en el paquete de Nova components. No requiere instalación adicional.

## Configuración

### 1. Configurar el Microservicio

Asegúrese de que el microservicio de Mikrotik esté corriendo y accesible.

### 2. Configurar en ISPGO

Use el panel de configuración de Nova para establecer:

- **URL del Microservicio**: `http://localhost:8000/api/v1`
- **Credenciales del Router**: Por cada router (scope_id)

### 3. Rutas de Configuración

| Ruta | Descripción |
|------|-------------|
| `mikrotik/general/enabled` | Habilitar módulo |
| `mikrotik/general/api_base_url` | URL del microservicio |
| `mikrotik/router_connection/host` | IP del router |
| `mikrotik/router_connection/username` | Usuario API |
| `mikrotik/router_connection/password` | Contraseña |

## Uso

### Via GraphQL

```graphql
# Consultar DHCP Leases
query {
  mikrotikDhcpLeases(router_id: "1") {
    success
    leases {
      address
      mac_address
      host_name
    }
  }
}

# Provisionar Servicio
mutation {
  mikrotikProvisionService(input: {
    service_id: "123"
    ip_address: "192.168.88.100"
    mac_address: "AA:BB:CC:DD:EE:FF"
  }) {
    success
    message
    queue_name
  }
}
```

## Documentación Adicional

- [Flujo de Provisión](provision_flow.md)
- [Uso de la API](api_usage.md)
- [Despliegue](deployment.md)

## Estructura del Módulo

```
nova-components/Mikrotik/src/
├── Exceptions/
│   └── MikrotikApiException.php    # Excepciones personalizadas
├── Http/
│   └── Middleware/
│       └── Authorize.php           # Middleware de autorización
├── Services/
│   ├── MikrotikApiClient.php       # Cliente HTTP para el microservicio
│   └── MikrotikProvisionService.php # Lógica de provisión
├── Settings/
│   ├── MikrotikConfigProvider.php  # Proveedor de configuración
│   └── SettingMikrotik.php         # Definición de campos de configuración
└── ToolServiceProvider.php         # Provider principal
```

## Soporte

Para problemas o preguntas, contactar al equipo de desarrollo.
