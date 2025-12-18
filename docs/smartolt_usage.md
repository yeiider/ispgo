# SmartOLT GraphQL Usage

This document provides examples of how to use the SmartOLT GraphQL API.

## Queries

### Get All OLTs
Retrieve a list of all configured OLTs.

```graphql
query GetOlts {
  smartOltOlts {
    id
    name
    olt_hardware_version
    ip
    telnet_port
    snmp_port
  }
}
```

### Get OLT Cards
Retrieve details of cards in a specific OLT.

```graphql
query GetOltCards($oltId: ID!) {
  smartOltOltCards(olt_id: $oltId) {
    slot
    type
    real_type
    ports
    software_version
    status
    role
    info_updated
  }
}
```

### Get OLT PON Ports
Retrieve details of PON ports for a specific OLT.

```graphql
query GetOltPonPorts($oltId: ID!) {
  smartOltOltPonPorts(olt_id: $oltId) {
    board
    pon_port
    pon_type
    admin_status
    operational_status
    description
    onus_count
    online_onus_count
    average_signal
  }
}
```

### Get Zones
Retrieve all zones.

```graphql
query GetZones {
  smartOltZones {
    id
    name
    imported_date
  }
}
```

### Get ODBs
Retrieve ODBs (Optical Distribution Boxes) for a specific zone.

```graphql
query GetOdbs($zoneId: ID!) {
  smartOltOdbs(zone_id: $zoneId) {
    id
    name
    nr_of_ports
    zone_name
  }
}
```

### Get Speed Profiles
Retrieve available speed profiles.

```graphql
query GetSpeedProfiles {
  smartOltSpeedProfiles {
    id
    name
    speed
    direction
    type
  }
}
```

### Get Unconfigured ONUs
Retrieve a list of unconfigured ONUs for a specific OLT.

```graphql
query GetUnconfiguredOnus($oltId: ID!) {
  smartOltUnconfiguredOnus(olt_id: $oltId) {
    sn
    board
    port
    onu
    onu_type_name
    status
    signal
  }
}
```

### Get OLTs Uptime and Temperature
Retrieve uptime and environment temperature for all OLTs.

```graphql
query GetOltsUptime {
  smartOltOltsUptime {
    olt_id
    olt_name
    uptime
    env_temp
  }
}
```

## Mutations

### Authorize ONU
Authorize a new ONU.

```graphql
mutation AuthorizeOnu($input: AuthorizeOnuInput!) {
  authorizeOnu(
    olt_id: "1"
    pon_type: "gpon"
    board: "1"
    port: "1"
    sn: "ZTEGC0XXXXXX"
    vlan: 100
    onu_mode: "Bridging"
    onu_type: "ZTE-F601"
    zone_id: "1"
    name: "Customer Name"
    speed_profile_id: "1"
  ) {
    success
    message
  }
}
```

### Reboot ONU
Reboot an ONU using its external ID.

```graphql
mutation RebootOnu($externalId: String!) {
  smartOltRebootOnu(external_id: $externalId) {
    success
    message
  }
}
```

### Enable ONU
Enable an ONU using its Serial Number.

```graphql
mutation EnableOnu($sn: String!) {
  smartOltEnableOnu(sn: $sn) {
    success
    message
  }
}
```

### Disable ONU
Disable an ONU using its Serial Number.

```graphql
mutation DisableOnu($sn: String!) {
  smartOltDisableOnu(sn: $sn) {
    success
    message
  }
}
```
