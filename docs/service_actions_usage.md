# Service Actions Mutations

This document describes the usage of the batch service action mutations. These mutations allow updating service details from SmartOLT and provisioning services in Mikrotik using background queues.

## 1. Update Services IP & MAC

This mutation triggers a background job for each provided Service ID. The job fetches the current status of the ONU from SmartOLT (using the Service's SN) and updates the local `services` table with the `service_ip` and `mac_address`.

### Mutation

```graphql
mutation UpdateServicesIpMac($ids: [ID!]!) {
  updateServicesIpMac(service_ids: $ids) {
    success
    message
  }
}
```

### Variables

```json
{
  "ids": [101, 102, 105]
}
```

### Response

```json
{
  "data": {
    "updateServicesIpMac": {
      "success": true,
      "message": "Se han encolado 3 servicios para actualizar IP y MAC."
    }
  }
}
```

---

## 2. Provision Services DHCP

This mutation triggers a background job for each provided Service ID. It requires the `dhcp_server` name to be specified. The job generates the Mikrotik Queue and DHCP Binding configuration and sends it to the router.

> **Note**: This action requires the Service to have a valid `mac_address` and `service_ip` (e.g., populated by the previous mutation).

### Mutation

```graphql
mutation ProvisionServicesDhcp($ids: [ID!]!, $server: String!) {
  provisionServicesDhcp(service_ids: $ids, dhcp_server: $server) {
    success
    message
  }
}
```

### Variables

```json
{
  "ids": [101, 102],
  "server": "dhcp1"
}
```

### Response

```json
{
  "data": {
    "provisionServicesDhcp": {
      "success": true,
      "message": "Se han encolado 2 servicios para provisión en Mikrotik."
    }
  }
}

---

## 3. Get DHCP Servers

This query fetches the available DHCP servers from a specific Mikrotik router. This is useful for populating the `dhcp_server` selection for the `provisionServicesDhcp` mutation.

### Query

```graphql
query GetDhcpServers($routerId: ID!) {
  mikrotikDhcpServers(router_id: $routerId) {
    success
    message
    count
    servers {
      name
      interface
      lease_time
      address_pool
    }
  }
}
```

### Variables

```json
{
  "routerId": 1
}
```

### Response

```json
{
  "data": {
    "mikrotikDhcpServers": {
      "success": true,
      "message": "Servidores DHCP obtenidos exitosamente",
      "count": 2,
      "servers": [
        {
          "name": "dhcp1",
          "interface": "vlan10",
          "lease_time": "10m",
          "address_pool": "pool1"
        },
        {
          "name": "dhcp2",
          "interface": "vlan20",
          "lease_time": "10m",
          "address_pool": "pool2"
        }
      ]
    }
  }
}
```
