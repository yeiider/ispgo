# GraphQL Actions - Documentación

Esta documentación describe todas las acciones (mutations) disponibles en la API GraphQL de ISPGO, basadas en las acciones de Laravel Nova.

## Índice

1. [Autenticación](#autenticación)
2. [Customers](#customers)
   - [Generate Invoice](#1-generate-invoice)
   - [Update Customer Status](#2-update-customer-status)

3. [Services](#services)
   - [Activate Service](#3-activate-service)
   - [Suspend Service](#4-suspend-service)

4. [Invoices](#invoices)
   - [Register Payment](#5-register-payment)
   - [Apply Discount](#6-apply-discount)
   - [Register Payment Promise](#7-register-payment-promise)

---

## Autenticación

Todas las mutations requieren autenticación mediante **Bearer Token** (Laravel Passport).

### Cómo obtener el token

1. **Login via API REST:**

```bash
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

Respuesta:
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

2. **Usar el token en GraphQL:**

Todas las peticiones GraphQL deben incluir el header de autorización:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

### Ejemplo con cURL

```bash
curl -X POST https://your-api.com/graphql \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "query": "mutation { generateInvoice(customer_id: \"1\") { success message } }"
  }'
```

### Ejemplo con JavaScript/Apollo Client

```javascript
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client';
import { setContext } from '@apollo/client/link/context';

const httpLink = createHttpLink({
  uri: 'https://your-api.com/graphql',
});

const authLink = setContext((_, { headers }) => {
  const token = localStorage.getItem('auth_token');
  return {
    headers: {
      ...headers,
      authorization: token ? `Bearer ${token}` : "",
    }
  }
});

const client = new ApolloClient({
  link: authLink.concat(httpLink),
  cache: new InMemoryCache()
});
```

### Roles y Permisos

Las mutations respetan los roles y permisos configurados en el sistema. Si un usuario no tiene permisos para ejecutar una acción, recibirá un error de autorización.

**Ejemplo de error sin autenticación:**
```json
{
  "errors": [
    {
      "message": "Unauthenticated.",
      "extensions": {
        "category": "authentication"
      }
    }
  ]
}
```

**Ejemplo de error sin permisos:**
```json
{
  "errors": [
    {
      "message": "This action is unauthorized.",
      "extensions": {
        "category": "authorization"
      }
    }
  ]
}
```

---

## Customers

### 1. Generate Invoice

**Descripción:** Genera una factura para un cliente basándose en sus servicios activos.

**Mutation:**
```graphql
mutation GenerateInvoice($customerId: ID!, $serviceId: ID) {
  generateInvoice(customer_id: $customerId, service_id: $serviceId) {
    success
    message
    invoice {
      id
      increment_id
      total
      status
      issue_date
      due_date
      customer {
        id
        first_name
        last_name
      }
      service {
        id
        service_ip
      }
      items {
        id
        description
        quantity
        unit_price
        subtotal
      }
    }
  }
}
```

**Variables:**
```json
{
  "customerId": "1",
  "serviceId": "5"
}
```

**Parámetros:**
- `customer_id` (ID, requerido): ID del cliente para el cual generar la factura
- `service_id` (ID, opcional): ID del servicio específico a facturar

**Respuesta exitosa:**
```json
{
  "data": {
    "generateInvoice": {
      "success": true,
      "message": "Factura generada exitosamente.",
      "invoice": {
        "id": "123",
        "increment_id": "INV-2024-001",
        "total": 50000,
        "status": "unpaid"
      }
    }
  }
}
```

**Errores posibles:**
- Cliente no existe
- Cliente no tiene servicios activos
- Error al generar la factura

---

### 2. Update Customer Status

**Descripción:** Actualiza el estado de un cliente (activo, inactivo, suspendido).

**Mutation:**
```graphql
mutation UpdateCustomerStatus($customerId: ID!, $status: String!) {
  updateCustomerStatus(customer_id: $customerId, status: $status) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "customerId": "1",
  "status": "active"
}
```

**Parámetros:**
- `customer_id` (ID, requerido): ID del cliente
- `status` (String, requerido): Nuevo estado del cliente
  - Valores permitidos: `active`, `inactive`, `suspended`

**Respuesta exitosa:**
```json
{
  "data": {
    "updateCustomerStatus": {
      "success": true,
      "message": "Customer status updated successfully!"
    }
  }
}
```

---

## Services

### 3. Activate Service

**Descripción:** Activa un servicio suspendido o inactivo.

**Mutation:**
```graphql
mutation ActivateService($serviceId: ID!) {
  activateService(service_id: $serviceId) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "serviceId": "5"
}
```

**Parámetros:**
- `service_id` (ID, requerido): ID del servicio a activar

**Respuesta exitosa:**
```json
{
  "data": {
    "activateService": {
      "success": true,
      "message": "Service activated successfully!"
    }
  }
}
```

---

### 4. Suspend Service

**Descripción:** Suspende un servicio activo.

**Mutation:**
```graphql
mutation SuspendService($serviceId: ID!) {
  suspendService(service_id: $serviceId) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "serviceId": "5"
}
```

**Parámetros:**
- `service_id` (ID, requerido): ID del servicio a suspender

**Respuesta exitosa:**
```json
{
  "data": {
    "suspendService": {
      "success": true,
      "message": "Services successfully suspended"
    }
  }
}
```

---

## Invoices

### 5. Register Payment

**Descripción:** Registra un pago completo para una factura.

**Mutation:**
```graphql
mutation RegisterPayment($invoiceId: ID!, $paymentMethod: String!, $notes: String) {
  registerPayment(invoice_id: $invoiceId, payment_method: $paymentMethod, notes: $notes) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "invoiceId": "123",
  "paymentMethod": "cash",
  "notes": "Pago recibido en efectivo"
}
```

**Parámetros:**
- `invoice_id` (ID, requerido): ID de la factura
- `payment_method` (String, requerido): Método de pago
  - Valores permitidos: `cash`, `transfer`, `card`, `online`
- `notes` (String, opcional): Notas adicionales sobre el pago

**Respuesta exitosa:**
```json
{
  "data": {
    "registerPayment": {
      "success": true,
      "message": "Payment generated successfully!"
    }
  }
}
```

**Errores posibles:**
- Factura no encontrada
- Factura ya pagada

---

### 6. Apply Discount

**Descripción:** Aplica un descuento a una factura (en monto fijo o porcentaje).

**Mutation:**
```graphql
mutation ApplyDiscount(
  $invoiceId: ID!
  $discount: Float!
  $isPercentage: Boolean!
  $includeTax: Boolean!
) {
  applyDiscount(
    invoice_id: $invoiceId
    discount: $discount
    is_percentage: $isPercentage
    include_tax: $includeTax
  ) {
    success
    message
  }
}
```

**Variables - Descuento porcentual:**
```json
{
  "invoiceId": "123",
  "discount": 10,
  "isPercentage": true,
  "includeTax": false
}
```

**Variables - Descuento fijo:**
```json
{
  "invoiceId": "123",
  "discount": 5000,
  "isPercentage": false,
  "includeTax": true
}
```

**Parámetros:**
- `invoice_id` (ID, requerido): ID de la factura
- `discount` (Float, requerido): Monto del descuento o porcentaje
- `is_percentage` (Boolean, requerido): Si el descuento es porcentual
  - `true`: descuento en porcentaje (10 = 10%)
  - `false`: descuento en monto fijo
- `include_tax` (Boolean, requerido): Si se incluyen impuestos en el cálculo
  - `true`: aplica descuento con impuestos
  - `false`: aplica descuento sin impuestos

**Respuesta exitosa:**
```json
{
  "data": {
    "applyDiscount": {
      "success": true,
      "message": "Discount applied successfully!"
    }
  }
}
```

**Errores posibles:**
- Factura no encontrada
- Factura ya pagada
- Descuento mayor que el total de la factura

---

### 7. Register Payment Promise

**Descripción:** Registra una promesa de pago para una factura con fecha compromiso.

**Mutation:**
```graphql
mutation RegisterPaymentPromise($invoiceId: ID!, $promiseDate: Date!, $notes: String) {
  registerPaymentPromise(invoice_id: $invoiceId, promise_date: $promiseDate, notes: $notes) {
    success
    message
    payment_promise {
      id
      amount
      promise_date
      notes
      status
      invoice {
        id
        increment_id
      }
      customer {
        id
        first_name
        last_name
      }
    }
  }
}
```

**Variables:**
```json
{
  "invoiceId": "123",
  "promiseDate": "2024-12-15",
  "notes": "Cliente promete pagar el 15 de diciembre"
}
```

**Parámetros:**
- `invoice_id` (ID, requerido): ID de la factura
- `promise_date` (Date, requerido): Fecha en que el cliente promete pagar (formato: YYYY-MM-DD)
- `notes` (String, opcional): Notas adicionales sobre la promesa de pago

**Respuesta exitosa:**
```json
{
  "data": {
    "registerPaymentPromise": {
      "success": true,
      "message": "Payment promise registered successfully!",
      "payment_promise": {
        "id": "45",
        "amount": 50000,
        "promise_date": "2024-12-15",
        "notes": "Cliente promete pagar el 15 de diciembre",
        "status": "pending"
      }
    }
  }
}
```

**Comportamiento adicional:**
- Si la configuración `enableServiceByPaymentPromise` está activa, el servicio asociado se activará automáticamente al registrar la promesa de pago.

**Errores posibles:**
- Factura no encontrada
- Ya existe una promesa de pago para esta factura

---

## Tipos de Respuesta

### GenerateInvoiceResult
```graphql
type GenerateInvoiceResult {
  success: Boolean!
  message: String
  invoice: Invoice
}
```

### ActionResult
```graphql
type ActionResult {
  success: Boolean!
  message: String
}
```

### PaymentPromiseResult
```graphql
type PaymentPromiseResult {
  success: Boolean!
  message: String
  payment_promise: PaymentPromise
}
```

---

## Manejo de Errores

Todas las mutations retornan un campo `success` que indica si la operación fue exitosa:

- `success: true` - La operación se completó correctamente
- `success: false` - Hubo un error, revisa el campo `message` para más detalles

**Ejemplo de error:**
```json
{
  "data": {
    "registerPayment": {
      "success": false,
      "message": "This invoice has already been paid!"
    }
  }
}
```

---

## Notas Importantes

1. **Autenticación:** Todas las mutations requieren autenticación válida
2. **Permisos:** El usuario debe tener los permisos correspondientes para ejecutar cada acción
3. **Validaciones:** Los parámetros son validados automáticamente por GraphQL según las reglas definidas
4. **Logs:** Todos los errores se registran en los logs de Laravel para debugging

---

## Ejemplos de Integración

### JavaScript/TypeScript (usando Apollo Client)

```typescript
import { gql, useMutation } from '@apollo/client';

const REGISTER_PAYMENT = gql`
  mutation RegisterPayment($invoiceId: ID!, $paymentMethod: String!, $notes: String) {
    registerPayment(invoice_id: $invoiceId, payment_method: $paymentMethod, notes: $notes) {
      success
      message
    }
  }
`;

function PaymentForm() {
  const [registerPayment] = useMutation(REGISTER_PAYMENT);

  const handleSubmit = async () => {
    const { data } = await registerPayment({
      variables: {
        invoiceId: "123",
        paymentMethod: "cash",
        notes: "Pago en efectivo"
      }
    });

    if (data.registerPayment.success) {
      console.log("Pago registrado exitosamente");
    }
  };

  return <button onClick={handleSubmit}>Registrar Pago</button>;
}
```

### cURL

```bash
curl -X POST https://your-api.com/graphql \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "query": "mutation($invoiceId: ID!, $paymentMethod: String!) { registerPayment(invoice_id: $invoiceId, payment_method: $paymentMethod) { success message } }",
    "variables": {
      "invoiceId": "123",
      "paymentMethod": "cash"
    }
  }'
```

---

## Soporte

Para preguntas o problemas con la API, contacta al equipo de desarrollo.
