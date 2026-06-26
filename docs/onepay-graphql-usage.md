# Uso de la API GraphQL de OnePay

Esta guía documenta los casos de uso para consumir la integración de OnePay a través de la API GraphQL del proyecto ISPGo.

## Queries (Consultas)

### 1. Consultar un Cobro Específico

Permite obtener los detalles de un cobro utilizando su `payment_id`. 
*Nota: El `payment_id` se puede obtener desde el modelo de la Factura (Invoice) consultando los campos `onepay_charge_id` o el identificador en `onepay_payment_link`.*

```graphql
query GetOnePayPayment($paymentId: String!) {
  onePayPayment(payment_id: $paymentId) {
    id
    status
    currency
    amount
    amount_label
    title
    description
    document_link
    phone
    payment_link
    created_at
    expiration_at
    paid_at
    customer {
      first_name
      last_name
      email
      phone
      user_type
    }
  }
}
```

**Variables:**
```json
{
  "paymentId": "9bf2bc44-28d4-4693-9896-7fc1fe1f5b65"
}
```

### 2. Consultar el Historial de Intentos de Cobro

Permite ver todos los intentos de pago realizados a un cobro específico, conociendo si fueron exitosos o fallidos y por qué.
*Nota: Al igual que en la consulta anterior, el `paymentId` corresponde al `onepay_charge_id` de la factura.*

```graphql
query GetOnePayPaymentIntents($paymentId: String!) {
  onePayPaymentIntents(payment_id: $paymentId) {
    id
    status
    currency
    amount
    amount_label
    payment_method_type
    remarks {
      code
      message
    }
    is_test
    created_at
    paid_at
  }
}
```

**Variables:**
```json
{
  "paymentId": "9e5ccd4a-d2f0-49dd-87fc-a0da752bd166"
}
```

### 3. Consultar un Cliente por Número de Documento

Permite consultar la existencia de un cliente en la plataforma de OnePay utilizando su documento de identidad.

```graphql
query GetOnePayCustomer($documentNumber: String!) {
  onePayCustomerByDocument(document_number: $documentNumber) {
    id
    first_name
    last_name
    email
    phone
    document_type
    document_number
    created_at
  }
}
```

**Variables:**
```json
{
  "documentNumber": "1060500333"
}
```

## Mutations (Mutaciones)

### 1. Crear un Cobro para una Factura

Genera un nuevo enlace de cobro de OnePay asociado a una factura existente.

```graphql
mutation CreateOnePayPayment($invoiceId: ID!) {
  createOnePayPayment(invoice_id: $invoiceId) {
    success
    message
    payment
  }
}
```

**Variables:**
```json
{
  "invoiceId": "1"
}
```

### 2. Reenviar un Cobro Existente

Reenvía el cobro a través de los medios configurados en OnePay para notificar al cliente nuevamente.

```graphql
mutation ResendOnePayPayment($invoiceId: ID!) {
  resendOnePayPayment(invoice_id: $invoiceId) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "invoiceId": "1"
}
```
