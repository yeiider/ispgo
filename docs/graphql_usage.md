# GraphQL API Usage

This document explains how to use the GraphQL API implemented in the `ispgo` application.

## Endpoint

The GraphQL endpoint is available at: `/graphql`

## Tools

You can use tools like [Postman](https://www.postman.com/), [Insomnia](https://insomnia.rest/), or [Altair GraphQL Client](https://altair.sirmuel.design/) to interact with the API.

## Example Queries

### Fetch Customers

To fetch a list of customers with their basic information:

```graphql
query {
  customers(first: 10) {
    data {
      id
      first_name
      last_name
      email_address
      phone_number
      identity_document
      document_type
      customer_status
      router_id
      router {
        id
        name
        ip_address
      }
    }
    paginatorInfo {
      currentPage
      lastPage
      total
    }
  }
}
```

### Fetch Customer with Relationships

To fetch a specific customer by ID, including their addresses, services, invoices, and router:

```graphql
query {
  customer(id: 1) {
    id
    first_name
    last_name
    document_type
    identity_document
    router {
      id
      name
      ip_address
    }
    addresses {
      id
      address
      city
    }
    services {
      id
      service_ip
      service_status
      router {
        name
        ip_address
      }
      plan {
        name
        monthly_price
      }
    }
    invoices {
      id
      increment_id
      total
      status
    }
  }
}
```

### Fetch Services

To fetch a list of services:

```graphql
query {
  services(first: 10) {
    data {
      id
      service_ip
      service_status
      router_id
      router {
        name
        ip_address
      }
      customer {
        first_name
        last_name
      }
      plan {
        name
        monthly_price
      }
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

### Fetch Invoices with Details

To fetch a list of invoices with their items, adjustments, credit notes, and router:

```graphql
query {
  invoices(first: 10) {
    data {
      id
      increment_id
      total
      status
      issue_date
      due_date
      router_id
      router {
        name
        ip_address
      }
      customer {
        first_name
        last_name
      }
      service {
        service_ip
      }
      items {
        description
        quantity
        subtotal
      }
      adjustments {
        kind
        amount
        label
      }
      creditNotes {
        amount
        reason
      }
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

### Fetch Billing Novedades

To fetch a list of billing novedades:

```graphql
query {
  billingNovedades(first: 10) {
    data {
      id
      type
      amount
      description
      applied
      effective_period
      service {
        service_ip
      }
      customer {
        first_name
        last_name
      }
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

### Fetch Routers

To fetch a list of routers:

```graphql
query {
  routers(first: 10) {
    data {
      id
      name
      ip_address
      description
      created_at
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

### Fetch Router with Relationships

To fetch a specific router with its associated customers, services, and invoices:

```graphql
query {
  router(id: 1) {
    id
    name
    ip_address
    description
    customers {
      id
      first_name
      last_name
    }
    services {
      id
      service_ip
      service_status
    }
    invoices {
      id
      increment_id
      total
      status
    }
  }
}
```

### Fetch Plans

To fetch a list of plans:

```graphql
query {
  plans(first: 10) {
    data {
      id
      name
      description
      monthly_price
      download_speed
      upload_speed
      status
      plan_type
      modality_type
      unlimited_data
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

### Fetch Plan with Relationships

To fetch a specific plan with its associated services:

```graphql
query {
  plan(id: 1) {
    id
    name
    description
    monthly_price
    download_speed
    upload_speed
    status
    plan_type
    modality_type
    services {
      id
      service_ip
      customer {
        first_name
        last_name
      }
    }
  }
}
```

## Schema

The schema defines the following types:

- **Customer**: Represents a customer. Includes `document_type` (required) and `router_id` (optional) for segmentation.
- **Address**: Represents a customer's address.
- **Service**: Represents a service subscribed by a customer. Includes `router_id` for segmentation.
- **Invoice**: Represents an invoice for a customer. Includes `router_id` for segmentation.
- **Router**: Represents a network router used for segmenting customers, services, and invoices.
- **Plan**: Represents a service plan with pricing and features.
- **InvoiceItem**: Represents an item in an invoice.
- **BillingNovedad**: Represents a billing novelty.
- **InvoiceAdjustment**: Represents an adjustment to an invoice.
- **CreditNote**: Represents a credit note.
- **PaymentPromise**: Represents a payment promise.

### Router Segmentation

The `router_id` field is used to segment customers, services, and invoices by network router. This allows you to organize and filter data based on different routers in your network infrastructure.

For the full schema definition, please refer to `graphql/schema.graphql`.
## Mutations

### Create Customer

To create a new customer (note: `document_type` is required):

```graphql
mutation {
  createCustomer(
    first_name: "John"
    last_name: "Doe"
    email_address: "john.doe@example.com"
    phone_number: "1234567890"
    identity_document: "123456789"
    document_type: "CC"
    router_id: 1
  ) {
    id
    first_name
    last_name
    document_type
    router {
      name
    }
    created_at
  }
}
```

Common `document_type` values:
- `CC`: Cédula de Ciudadanía
- `CE`: Cédula de Extranjería
- `NIT`: Número de Identificación Tributaria
- `TI`: Tarjeta de Identidad
- `PAS`: Pasaporte

### Update Customer

To update an existing customer:

```graphql
mutation {
  updateCustomer(
    id: 1
    first_name: "Jane"
    document_type: "CE"
    router_id: 2
  ) {
    id
    first_name
    last_name
    document_type
    router {
      name
    }
  }
}
```

### Delete Customer

To delete a customer:

```graphql
mutation {
  deleteCustomer(id: 1) {
    id
    first_name
  }
}
```

### Create Service

To create a new service for a customer:

```graphql
mutation {
  createService(
    customer_id: 1
    plan_id: 2
    service_ip: "192.168.1.100"
    service_status: "active"
    router_id: 1
  ) {
    id
    service_ip
    service_status
    router {
      name
      ip_address
    }
    customer {
      first_name
    }
  }
}
```

### Update Service

To update an existing service:

```graphql
mutation {
  updateService(
    id: 1
    service_status: "suspended"
    router_id: 2
  ) {
    id
    service_status
    router {
      name
    }
  }
}
```

### Create Router

To create a new router:

```graphql
mutation {
  createRouter(
    name: "Router-Principal"
    ip_address: "192.168.1.1"
    description: "Router principal para zona norte"
  ) {
    id
    name
    ip_address
    description
    created_at
  }
}
```

### Update Router

To update an existing router:

```graphql
mutation {
  updateRouter(
    id: 1
    name: "Router-Principal-Actualizado"
    ip_address: "192.168.1.2"
  ) {
    id
    name
    ip_address
  }
}
```

### Create Plan

To create a new plan (only required fields):

```graphql
mutation {
  createPlan(
    name: "Plan Básico 50MB"
    monthly_price: 50000
    status: "active"
  ) {
    id
    name
    monthly_price
    status
    created_at
  }
}
```

To create a plan with all details:

```graphql
mutation {
  createPlan(
    name: "Plan Premium 100MB"
    monthly_price: 80000
    status: "active"
    description: "Plan de alta velocidad con soporte 24/7"
    download_speed: 100
    upload_speed: 50
    unlimited_data: true
    plan_type: "internet"
    modality_type: "postpaid"
    connection_type: "Fiber Optic"
    contract_period: "12 months"
  ) {
    id
    name
    description
    monthly_price
    download_speed
    upload_speed
    status
    plan_type
  }
}
```

**Plan Types:**
- `internet`: Internet service
- `television`: TV service
- `telephonic`: Phone service

**Modality Types:**
- `prepaid`: Prepaid service
- `postpaid`: Postpaid service

**Status:**
- `active`: Active plan
- `inactive`: Inactive plan

### Update Plan

To update an existing plan:

```graphql
mutation {
  updatePlan(
    id: 1
    monthly_price: 85000
    download_speed: 120
  ) {
    id
    name
    monthly_price
    download_speed
    status
  }
}
```

### Delete Plan

To delete a plan:

```graphql
mutation {
  deletePlan(id: 1) {
    id
    name
  }
}
```
