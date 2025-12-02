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
    }
    paginatorInfo {
      currentPage
      lastPage
    }
  }
}
```

### Fetch Customer with Relationships

To fetch a specific customer by ID, including their addresses, services, and invoices:

```graphql
query {
  customer(id: 1) {
    id
    first_name
    last_name
    addresses {
      id
      address
      city
    }
    services {
      id
      service_ip
      service_status
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
      customer {
        first_name
        last_name
      }
    }
  }
}
```

### Fetch Invoices with Details

To fetch a list of invoices with their items, adjustments, and credit notes:

```graphql
query {
  invoices(first: 10) {
    data {
      id
      increment_id
      total
      status
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
      service {
        service_ip
      }
    }
  }
}
```

## Schema

The schema defines the following types:

- **Customer**: Represents a customer.
- **Address**: Represents a customer's address.
- **Service**: Represents a service subscribed by a customer.
- **Invoice**: Represents an invoice for a customer.
- **Plan**: Represents a service plan.
- **InvoiceItem**: Represents an item in an invoice.
- **BillingNovedad**: Represents a billing novelty.
- **InvoiceAdjustment**: Represents an adjustment to an invoice.
- **CreditNote**: Represents a credit note.
- **PaymentPromise**: Represents a payment promise.

For the full schema definition, please refer to `graphql/schema.graphql`.
## Mutations

### Create Customer

To create a new customer:

```graphql
mutation {
  createCustomer(
    first_name: "John"
    last_name: "Doe"
    email_address: "john.doe@example.com"
    phone_number: "1234567890"
    identity_document: "123456789"
  ) {
    id
    first_name
    last_name
    created_at
  }
}
```

### Update Customer

To update an existing customer:

```graphql
mutation {
  updateCustomer(
    id: 1
    first_name: "Jane"
  ) {
    id
    first_name
    last_name
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
  ) {
    id
    service_ip
    service_status
    customer {
      first_name
    }
  }
}
```
