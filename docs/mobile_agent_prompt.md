# Prompt for Mobile App Agent: NAP Management & Radar

You are an expert mobile developer agent building a "NAP Manager" application for ISP field technicians.
Your goal is to implement screens that allow technicians to:
1.  **Radar View**: Find nearby NAP boxes based on their current GPS location.
2.  **NAP Details**: View ports, status, and assigned services of a specific NAP box.
3.  **Manage NAPs**: Create new boxes, update existing ones, add ports, and assign customer services to ports.

## Backend GraphQL API

The backend provides a GraphQL API to interact with the system. Below are the specific queries and mutations designed for the mobile app.

### 1. Radar Query (Geo-spatial)
Use this query to find NAP boxes within a specific radius (default 500 meters) of the user's current position. This is used for the "Radar" or "Map" view.

**Query Field:** `nearbyNapBoxes`
**Arguments:**
- `latitude`: Float! (Required)
- `longitude`: Float! (Required)
- `radius`: Int (Optional, in meters, default: 500)

**Example Query:**
```graphql
query Radar($lat: Float!, $lng: Float!) {
  nearbyNapBoxes(latitude: $lat, longitude: $lng, radius: 500) {
    id
    name
    code
    address
    latitude
    longitude
    status
    available_ports_count
    fiber_color
    # The backend returns results ordered by distance
  }
}
```

### 2. NAP Details & Port Management
To view all details of a NAP, including its ports and connected services.

**Query:** `napBox`
```graphql
query NapDetails($id: ID!) {
  napBox(id: $id) {
    id
    name
    code
    address
    fiber_color
    router_id
    ports {
      id
      port_number
      port_name
      status
      code # Port code/label
      color # Fiber strand color
      service {
        id
        service_status
        sn
        customer_id
      }
    }
  }
}
```

### 3. Creating & Updating NAPs
Technicians can register new NAP boxes installed in the field.

**Mutation:** `createNapBox`
```graphql
mutation CreateNAP($input: CreateNapBoxInput!) {
  createNapBox(input: $input) {
    id
    name
    code
  }
}
```

**Input Variables Example:**
```json
{
  "input": {
    "name": "NAP-Sector-A",
    "code": "NAP-055",
    "latitude": 4.12345,
    "longitude": -74.12345,
    "address": "Street 1 #2-3",
    "capacity": 16,
    "fiber_color": "Blue",
    "router_id": 1
  }
}
```

### 4. Port Management
Add ports to a NAP or assign a service to a port.

**Create Port:**
```graphql
mutation CreatePort($input: CreateNapPortInput!) {
  createNapPort(input: $input) {
    id
    port_number
  }
}
```

**Assign Service:**
```graphql
mutation AssignService($serviceId: ID!, $portId: ID!) {
  assignServiceToNapPort(service_id: $serviceId, nap_port_id: $portId) {
    id
    status
    service {
      id
      sn
    }
  }
}
```

## Full GraphQL Schema Definition

Use the following schema definition to generate your API client types and models.

```graphql
scalar JSON @scalar(class: "Nuwave\\Lighthouse\\Schema\\Types\\Scalars\\Json")

type NapBox {
  id: ID!
  name: String!
  code: String
  address: String
  latitude: Float
  longitude: Float
  status: String
  capacity: Int
  technology_type: String
  installation_date: String
  brand: String
  model: String
  distribution_order: Int
  parent_nap_id: ID
  router_id: ID
  fiber_color: String
  ports: [NapPort!] @hasMany(relation: "ports")
  available_ports_count: Int
  # Services related to this box (via ports)
  services: [Service!]! @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napBoxServices")
}

type NapBoxPaginator {
  data: [NapBox!]!
  paginatorInfo: PaginatorInfo!
}

type PaginatorInfo {
  count: Int!
  currentPage: Int!
  firstItem: Int
  hasMorePages: Boolean!
  lastItem: Int
  lastPage: Int!
  perPage: Int!
  total: Int!
}

type NapPort {
  id: ID!
  nap_box_id: ID!
  port_number: Int!
  port_name: String
  status: String
  connection_type: String
  service_id: ID
  code: String
  color: String
  last_signal_check: String
  signal_strength: Float
  port_config: JSON
  notes: String
  technician_notes: String
  last_maintenance: String
  warranty_until: String
  # Relations
  service: Service @belongsTo(relation: "service")
  napBox: NapBox @belongsTo(relation: "napBox")
}

input CreateNapBoxInput {
  name: String!
  code: String!
  address: String
  latitude: Float
  longitude: Float
  status: String
  capacity: Int
  technology_type: String
  installation_date: String
  brand: String
  model: String
  distribution_order: Int
  parent_nap_id: ID
  router_id: ID
  fiber_color: String
}

input UpdateNapBoxInput {
  name: String
  code: String
  address: String
  latitude: Float
  longitude: Float
  status: String
  capacity: Int
  technology_type: String
  installation_date: String
  brand: String
  model: String
  distribution_order: Int
  parent_nap_id: ID
  router_id: ID
  fiber_color: String
}

input CreateNapPortInput {
  nap_box_id: ID!
  port_number: Int!
  port_name: String
  status: String
  connection_type: String
  service_id: ID
  code: String
  color: String
  last_signal_check: String
  signal_strength: Float
  port_config: JSON
  notes: String
  technician_notes: String
  last_maintenance: String
  warranty_until: String
}

input UpdateNapPortInput {
  port_name: String
  status: String
  connection_type: String
  service_id: ID
  code: String
  color: String
  last_signal_check: String
  signal_strength: Float
  port_config: JSON
  notes: String
  technician_notes: String
  last_maintenance: String
  warranty_until: String
}

extend type Query {
  napBoxes(router_id: ID, first: Int = 15, page: Int): NapBoxPaginator @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napBoxes")
  napBox(id: ID!): NapBox @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napBox")
  napPorts(nap_box_id: ID!): [NapPort!]! @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napPorts")
  napPort(id: ID!): NapPort @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napPort")
  availableNapPorts(nap_box_id: ID!): [NapPort!]! @field(resolver: "App\\GraphQL\\Queries\\NapQuery@availableNapPorts")
  napBoxServices(nap_box_id: ID!): [Service!]! @field(resolver: "App\\GraphQL\\Queries\\NapQuery@napBoxServices")
  # Radar: Find nearby boxes
  nearbyNapBoxes(latitude: Float!, longitude: Float!, radius: Int = 500): [NapBox!]! @field(resolver: "App\\GraphQL\\Queries\\NapQuery@nearbyNapBoxes")
}

extend type Mutation {
  createNapBox(input: CreateNapBoxInput!): NapBox @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@createNapBox")
  updateNapBox(id: ID!, input: UpdateNapBoxInput!): NapBox @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@updateNapBox")
  createNapPort(input: CreateNapPortInput!): NapPort @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@createNapPort")
  updateNapPort(id: ID!, input: UpdateNapPortInput!): NapPort @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@updateNapPort")
  assignServiceToNapPort(service_id: ID!, nap_port_id: ID!): NapPort @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@assignServiceToNapPort")
  releaseNapPort(nap_port_id: ID!): NapPort @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@releaseNapPort")
  assignRouterToNapBox(nap_box_id: ID!, router_id: ID!): NapBox @field(resolver: "App\\GraphQL\\Mutations\\NapMutation@assignRouterToNapBox")
}
```
