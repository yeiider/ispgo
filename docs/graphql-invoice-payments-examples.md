# Ejemplos de Uso - API GraphQL InvoicePayments

## 🎯 Queries

### 1. Listar todos los abonos (con paginación)

```graphql
query ListInvoicePayments {
  invoicePayments(first: 20, page: 1, orderBy: [{field: "created_at", order: DESC}]) {
    paginatorInfo {
      count
      currentPage
      total
      lastPage
    }
    data {
      id
      amount
      paymentDate
      paymentMethod
      referenceNumber
      notes
      invoice {
        id
        incrementId
        total
        status
      }
      user {
        id
        name
        email
      }
      createdAt
    }
  }
}
```

### 2. Obtener abonos de una factura específica

Usando el query personalizado:

```graphql
query GetInvoicePayments($invoiceId: ID!) {
  invoicePaymentsByInvoice(invoiceId: $invoiceId) {
    id
    amount
    paymentDate
    paymentMethod
    referenceNumber
    notes
    user {
      id
      name
    }
    createdAt
  }
}
```

Variables:
```json
{
  "invoiceId": "123"
}
```

O usando paginación con filtro:

```graphql
query GetInvoicePaymentsFiltered($invoiceId: ID!) {
  invoicePayments(invoiceId: $invoiceId, first: 50) {
    data {
      id
      amount
      paymentDate
      paymentMethod
      referenceNumber
      notes
      createdAt
    }
  }
}
```

### 3. Obtener un abono específico

```graphql
query GetSinglePayment($id: ID!) {
  invoicePayment(id: $id) {
    id
    amount
    paymentDate
    paymentMethod
    referenceNumber
    notes
    paymentSupport
    additionalInformation
    invoice {
      id
      incrementId
      total
      totalPaid
      realOutstandingBalance
      status
      isFullyPaid
      customer {
        id
        firstName
        lastName
        identityDocument
      }
    }
    user {
      id
      name
      email
    }
    createdAt
    updatedAt
  }
}
```

Variables:
```json
{
  "id": "45"
}
```

### 4. Obtener factura con todos sus abonos

```graphql
query GetInvoiceWithPayments($id: ID!) {
  invoice(id: $id) {
    id
    incrementId
    total
    status
    issueDate
    dueDate
    
    # Campos agregados de payment
    totalPaid
    realOutstandingBalance
    isFullyPaid
    
    # Lista de abonos
    payments {
      id
      amount
      paymentDate
      paymentMethod
      referenceNumber
      notes
      user {
        name
      }
      createdAt
    }
    
    customer {
      id
      firstName
      lastName
      identityDocument
      emailAddress
    }
    
    service {
      id
      serviceIp
      serviceStatus
    }
  }
}
```

Variables:
```json
{
  "id": "123"
}
```

---

## ✏️ Mutations

### 1. Crear un abono parcial

```graphql
mutation CreatePartialPayment($input: CreateInvoicePaymentInput!) {
  createInvoicePayment(input: $input) {
    id
    amount
    paymentDate
    paymentMethod
    referenceNumber
    notes
    invoice {
      id
      incrementId
      total
      totalPaid
      realOutstandingBalance
      status
      isFullyPaid
    }
    user {
      id
      name
    }
    createdAt
  }
}
```

Variables:
```json
{
  "input": {
    "invoiceId": "123",
    "amount": 50000,
    "paymentDate": "2026-01-14",
    "paymentMethod": "TRANSFER",
    "referenceNumber": "TRX-20260114-001",
    "notes": "Abono parcial - Primera cuota"
  }
}
```

### 2. Crear abono con efectivo

```graphql
mutation CreateCashPayment {
  createInvoicePayment(input: {
    invoiceId: "456"
    amount: 100000
    paymentDate: "2026-01-14"
    paymentMethod: CASH
    notes: "Pago en efectivo recibido en oficina"
  }) {
    id
    amount
    paymentDate
    invoice {
      id
      status
      isFullyPaid
      realOutstandingBalance
    }
  }
}
```

### 3. Actualizar un abono

```graphql
mutation UpdatePayment($id: ID!, $input: UpdateInvoicePaymentInput!) {
  updateInvoicePayment(id: $id, input: $input) {
    id
    amount
    paymentDate
    paymentMethod
    referenceNumber
    notes
    invoice {
      id
      totalPaid
      realOutstandingBalance
      status
    }
    updatedAt
  }
}
```

Variables:
```json
{
  "id": "45",
  "input": {
    "amount": 75000,
    "notes": "Monto corregido - Abono ajustado"
  }
}
```

### 4. Actualizar método de pago

```graphql
mutation UpdatePaymentMethod($id: ID!) {
  updateInvoicePayment(id: $id, input: {
    paymentMethod: CARD
    referenceNumber: "CARD-20260114-456"
  }) {
    id
    paymentMethod
    referenceNumber
    updatedAt
  }
}
```

Variables:
```json
{
  "id": "45"
}
```

### 5. Eliminar un abono

```graphql
mutation DeletePayment($id: ID!) {
  deleteInvoicePayment(id: $id) {
    success
    message
    invoice {
      id
      incrementId
      totalPaid
      realOutstandingBalance
      status
      isFullyPaid
    }
  }
}
```

Variables:
```json
{
  "id": "45"
}
```

---

## 🔍 Queries Avanzadas

### 1. Obtener usuario con todos sus abonos registrados

```graphql
query GetUserWithPayments($id: ID!) {
  user(id: $id) {
    id
    name
    email
    registeredPayments {
      id
      amount
      paymentDate
      paymentMethod
      referenceNumber
      invoice {
        incrementId
        customer {
          firstName
          lastName
        }
      }
      createdAt
    }
  }
}
```

### 2. Listar facturas con estado de pago

```graphql
query ListInvoicesWithPaymentStatus {
  invoices(first: 20, status: "unpaid") {
    data {
      id
      incrementId
      total
      totalPaid
      realOutstandingBalance
      isFullyPaid
      status
      dueDate
      
      customer {
        firstName
        lastName
      }
      
      payments {
        id
        amount
        paymentDate
      }
    }
  }
}
```

### 3. Buscar facturas completamente pagadas

```graphql
query FullyPaidInvoices {
  invoices(first: 50, status: "paid") {
    data {
      id
      incrementId
      total
      totalPaid
      isFullyPaid
      dueDate
      
      payments {
        id
        amount
        paymentDate
        paymentMethod
        user {
          name
        }
      }
    }
  }
}
```

---

## 🛠️ Casos de Uso Prácticos

### Caso 1: Cliente paga parcialmente una factura

```graphql
# 1. Ver factura actual
query {
  invoice(id: "123") {
    incrementId
    total
    totalPaid
    realOutstandingBalance
    isFullyPaid
  }
}

# Resultado esperado:
# {
#   "incrementId": "0000000123",
#   "total": 100000,
#   "totalPaid": 0,
#   "realOutstandingBalance": 100000,
#   "isFullyPaid": false
# }

# 2. Registrar primer abono
mutation {
  createInvoicePayment(input: {
    invoiceId: "123"
    amount: 30000
    paymentDate: "2026-01-14"
    paymentMethod: TRANSFER
    referenceNumber: "TRX001"
  }) {
    id
    amount
    invoice {
      totalPaid
      realOutstandingBalance
      isFullyPaid
      status
    }
  }
}

# Resultado:
# {
#   "id": "1",
#   "amount": 30000,
#   "invoice": {
#     "totalPaid": 30000,
#     "realOutstandingBalance": 70000,
#     "isFullyPaid": false,
#     "status": "unpaid"
#   }
# }
```

### Caso 2: Completar pago en múltiples abonos

```graphql
# Abono 2
mutation {
  createInvoicePayment(input: {
    invoiceId: "123"
    amount: 40000
    paymentDate: "2026-01-15"
    paymentMethod: CASH
  }) {
    invoice {
      totalPaid
      realOutstandingBalance
      status
    }
  }
}

# Abono final
mutation {
  createInvoicePayment(input: {
    invoiceId: "123"
    amount: 30000
    paymentDate: "2026-01-16"
    paymentMethod: CARD
    referenceNumber: "CARD789"
  }) {
    invoice {
      totalPaid
      realOutstandingBalance
      isFullyPaid
      status
    }
  }
}

# Resultado final:
# {
#   "invoice": {
#     "totalPaid": 100000,
#     "realOutstandingBalance": 0,
#     "isFullyPaid": true,
#     "status": "paid"
#   }
# }
```

### Caso 3: Corregir abono registrado incorrectamente

```graphql
# 1. Ver abono actual
query {
  invoicePayment(id: "5") {
    amount
    invoice {
      totalPaid
      realOutstandingBalance
    }
  }
}

# 2. Actualizar con monto correcto
mutation {
  updateInvoicePayment(id: "5", input: {
    amount: 55000
    notes: "Monto corregido por error de digitación"
  }) {
    amount
    notes
    invoice {
      totalPaid
      realOutstandingBalance
    }
  }
}
```

### Caso 4: Reversar un abono

```graphql
mutation {
  deleteInvoicePayment(id: "5") {
    success
    message
    invoice {
      id
      incrementId
      totalPaid
      realOutstandingBalance
      status
      isFullyPaid
    }
  }
}
```

---

## 📊 Enums Disponibles

### PaymentMethod

```
CASH      - Efectivo
TRANSFER  - Transferencia bancaria
CARD      - Tarjeta de crédito/débito
ONLINE    - Pago en línea
```

---

## 🔑 Autenticación

Todas las queries y mutations requieren autenticación. Incluye el token en el header:

```http
POST /graphql
Content-Type: application/json
Authorization: Bearer {tu_token_aqui}
```

---

## ⚠️ Validaciones

- El monto debe ser mayor a 0.01
- El monto no puede exceder el saldo pendiente de la factura
- La fecha de pago es requerida
- El invoice_id debe existir
- Solo usuarios autenticados pueden crear/modificar/eliminar abonos

---

## 🎓 Tips

1. **Usa fragments** para reutilizar campos:
```graphql
fragment PaymentDetails on InvoicePayment {
  id
  amount
  paymentDate
  paymentMethod
  referenceNumber
  notes
}

query {
  invoicePayments(first: 10) {
    data {
      ...PaymentDetails
    }
  }
}
```

2. **Usa aliases** para múltiples queries:
```graphql
query {
  unpaid: invoicePayments(first: 10, invoiceId: "123") {
    data { id amount }
  }
  
  recent: invoicePayments(first: 5, orderBy: [{field: "created_at", order: DESC}]) {
    data { id amount paymentDate }
  }
}
```

3. **Solicita solo lo que necesites** para optimizar rendimiento

---

¡Listo para usar! 🚀
