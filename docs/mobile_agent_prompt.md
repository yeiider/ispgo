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

### 5. Querying Routers
To get a list of available routers in the system (needed when creating NAP boxes or filtering data).

**Query:** `routers`
```graphql
query GetRouters($first: Int = 10, $page: Int = 1) {
  routers(first: $first, page: $page) {
    data {
      id
      name
      ip_address
      description
    }
    paginatorInfo {
      total
      currentPage
      hasMorePages
    }
  }
}
```

**Get Single Router:**
```graphql
query GetRouter($id: ID!) {
  router(id: $id) {
    id
    name
    ip_address
    description
  }
}
```

### 6. Searching Customers and Their Services
When assigning a service to a NAP port, you need to search for customers and get their service IDs.

**Search Customers:**
```graphql
query SearchCustomers($search: String, $first: Int = 10) {
  customers(search: $search, first: $first) {
    data {
      id
      first_name
      last_name
      identity_document
      phone_number
      email_address
      customer_status
      services {
        id
        service_status
        service_ip
        sn
        mac_address
        service_type
        plan {
          id
          name
          download_speed
          upload_speed
        }
      }
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

**Get Customer by ID with Services:**
```graphql
query GetCustomer($id: ID!) {
  customer(id: $id) {
    id
    first_name
    last_name
    identity_document
    phone_number
    email_address
    services {
      id
      service_status
      service_ip
      sn
      mac_address
      router_id
      plan {
        id
        name
      }
    }
  }
}
```

**Variables Example for Customer Search:**
```json
{
  "search": "John",
  "first": 10
}
```

### 7. Complete Service Assignment Workflow
Here's the complete flow for assigning a service to a NAP port:

1. **Search for Customer** using `customers` query with search parameter
2. **Select Service** from the customer's services list (get the `service_id`)
3. **Find Available Port** in the NAP box using `availableNapPorts` query
4. **Assign Service to Port** using `assignServiceToNapPort` mutation

**Complete Example:**
```graphql
# Step 1: Search customer
query SearchCustomer {
  customers(search: "Garcia", first: 5) {
    data {
      id
      first_name
      last_name
      services {
        id
        service_status
        sn
      }
    }
  }
}

# Step 2: Get available ports
query GetAvailablePorts($napBoxId: ID!) {
  availableNapPorts(nap_box_id: $napBoxId) {
    id
    port_number
    status
  }
}

# Step 3: Assign service to port
mutation AssignServiceToPort($serviceId: ID!, $portId: ID!) {
  assignServiceToNapPort(service_id: $serviceId, nap_port_id: $portId) {
    id
    port_number
    status
    service {
      id
      sn
      customer {
        first_name
        last_name
      }
    }
  }
}
```

## Additional Important Queries

### Get Services by Router
```graphql
query GetServicesByRouter($routerId: ID!, $first: Int = 10) {
  services(router_id: $routerId, first: $first) {
    data {
      id
      service_ip
      service_status
      sn
      customer {
        id
        first_name
        last_name
      }
    }
  }
}
```

### Get Services by Customer
```graphql
query GetServicesByCustomer($customerId: ID!, $first: Int = 10) {
  services(customer_id: $customerId, first: $first) {
    data {
      id
      service_ip
      service_status
      sn
      mac_address
    }
  }
}
```

## Summary

This mobile app should provide technicians with:
- **Radar/Map view** to find nearby NAP boxes
- **Customer search** to find services that need to be assigned
- **Router listing** for filtering and NAP creation
- **Service assignment** workflow to connect customers to physical ports
- **NAP and Port management** for field installations

All queries support pagination where applicable, and the service assignment workflow ensures proper tracking of which customer service is connected to which physical port in the network infrastructure.
