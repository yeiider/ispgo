# Guía de Implementación GraphQL para InvoicePayments

Esta guía te ayudará a implementar una API GraphQL usando Lighthouse PHP para gestionar abonos a facturas, aprovechando el backend existente.

---

## 🚀 Instalación

### 1. Instalar Lighthouse PHP

```bash
composer require nuwave/lighthouse
```

### 2. Publicar configuración

```bash
php artisan vendor:publish --tag=lighthouse-schema
php artisan vendor:publish --tag=lighthouse-config
```

---

## 📋 Schema GraphQL

**Archivo:** `graphql/invoice-payments.graphql`

```graphql
"Representa un abono o pago aplicado a una factura"
type InvoicePayment {
  "ID único del abono"
  id: ID!
  
  "Factura a la que pertenece este abono"
  invoice: Invoice! @belongsTo
  
  "Usuario que registró el abono"
  user: User! @belongsTo
  
  "Monto del abono"
  amount: Float!
  
  "Fecha en que se realizó el pago"
  paymentDate: Date!
  
  "Método de pago utilizado"
  paymentMethod: PaymentMethod
  
  "Número de referencia o transacción"
  referenceNumber: String
  
  "Notas adicionales sobre el pago"
  notes: String
  
  "Ruta al comprobante de pago"
  paymentSupport: String
  
  "Información adicional en formato JSON"
  additionalInformation: JSON
  
  "Fecha de creación del registro"
  createdAt: DateTime!
  
  "Última actualización del registro"
  updatedAt: DateTime!
}

"Métodos de pago disponibles"
enum PaymentMethod {
  CASH @enum(value: "cash")
  TRANSFER @enum(value: "transfer")
  CARD @enum(value: "card")
  ONLINE @enum(value: "online")
}

"Input para crear un nuevo abono"
input CreateInvoicePaymentInput {
  "ID de la factura"
  invoiceId: ID! @rules(apply: ["required", "exists:invoices,id"])
  
  "Monto del abono"
  amount: Float! @rules(apply: ["required", "numeric", "min:0.01"])
  
  "Fecha del pago"
  paymentDate: Date! @rules(apply: ["required", "date"])
  
  "Método de pago"
  paymentMethod: PaymentMethod
  
  "Número de referencia"
  referenceNumber: String @rules(apply: ["max:255"])
  
  "Notas sobre el pago"
  notes: String
  
  "Comprobante de pago"
  paymentSupport: String
}

"Input para actualizar un abono existente"
input UpdateInvoicePaymentInput {
  "Nuevo monto (opcional)"
  amount: Float @rules(apply: ["numeric", "min:0.01"])
  
  "Nueva fecha (opcional)"
  paymentDate: Date @rules(apply: ["date"])
  
  "Nuevo método de pago (opcional)"
  paymentMethod: PaymentMethod
  
  "Nuevo número de referencia (opcional)"
  referenceNumber: String @rules(apply: ["max:255"])
  
  "Nuevas notas (opcional)"
  notes: String
}

"Respuesta al eliminar un abono"
type DeleteInvoicePaymentPayload {
  "Indica si se eliminó correctamente"
  success: Boolean!
  
  "Mensaje de respuesta"
  message: String!
  
  "Factura actualizada tras eliminar el abono"
  invoice: Invoice
}

extend type Query @guard {
  "Obtener todos los abonos (opcionalmente filtrados por factura)"
  invoicePayments(
    "Filtrar por ID de factura"
    invoiceId: ID @eq
    
    "Ordenar por campo"
    orderBy: [OrderByClause!] @orderBy
  ): [InvoicePayment!]! 
    @paginate(defaultCount: 20)
    @can(ability: "viewAnyInvoicePayment")
  
  "Obtener un abono específico por ID"
  invoicePayment(
    "ID del abono"
    id: ID! @eq
  ): InvoicePayment 
    @find
    @can(ability: "viewInvoicePayment", find: "id")
  
  "Obtener abonos de una factura específica"
  invoicePaymentsByInvoice(
    "ID de la factura"
    invoiceId: ID!
  ): [InvoicePayment!]! 
    @field(resolver: "App\\GraphQL\\Queries\\InvoicePaymentQueries@getByInvoice")
    @can(ability: "viewAnyInvoicePayment")
}

extend type Mutation @guard {
  "Crear un nuevo abono a una factura"
  createInvoicePayment(
    input: CreateInvoicePaymentInput! @spread
  ): InvoicePayment! 
    @field(resolver: "App\\GraphQL\\Mutations\\InvoicePaymentMutations@create")
    @can(ability: "createInvoicePayment")
  
  "Actualizar un abono existente"
  updateInvoicePayment(
    "ID del abono a actualizar"
    id: ID!
    
    "Datos a actualizar"
    input: UpdateInvoicePaymentInput! @spread
  ): InvoicePayment! 
    @field(resolver: "App\\GraphQL\\Mutations\\InvoicePaymentMutations@update")
    @can(ability: "updateInvoicePayment", find: "id")
  
  "Eliminar un abono"
  deleteInvoicePayment(
    "ID del abono a eliminar"
    id: ID!
  ): DeleteInvoicePaymentPayload! 
    @field(resolver: "App\\GraphQL\\Mutations\\InvoicePaymentMutations@delete")
    @can(ability: "deleteInvoicePayment", find: "id")
}

"Tipo Invoice extendido con relación de abonos"
extend type Invoice {
  "Lista de abonos aplicados a esta factura"
  payments: [InvoicePayment!]! @hasMany
  
  "Total pagado (suma de todos los abonos)"
  totalPaid: Float! @method(name: "getTotalPaidAttribute")
  
  "Saldo pendiente real"
  realOutstandingBalance: Float! @method(name: "getRealOutstandingBalanceAttribute")
  
  "Indica si la factura está completamente pagada"
  isFullyPaid: Boolean! @method(name: "isFullyPaid")
}
```

---

## 🔧 Resolvers

### Query Resolver

**Archivo:** `app/GraphQL/Queries/InvoicePaymentQueries.php`

```php
<?php

namespace App\GraphQL\Queries;

use App\Services\Invoice\InvoicePaymentService;

class InvoicePaymentQueries
{
    protected InvoicePaymentService $service;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Obtener abonos de una factura específica
     *
     * @param mixed $root
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByInvoice($root, array $args)
    {
        return $this->service->getByInvoiceId($args['invoiceId']);
    }
}
```

### Mutation Resolver

**Archivo:** `app/GraphQL/Mutations/InvoicePaymentMutations.php`

```php
<?php

namespace App\GraphQL\Mutations;

use App\Services\Invoice\InvoicePaymentService;
use Illuminate\Support\Facades\Auth;

class InvoicePaymentMutations
{
    protected InvoicePaymentService $service;

    public function __construct(InvoicePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Crear un nuevo abono
     *
     * @param mixed $root
     * @param array $args
     * @return \App\Models\Invoice\InvoicePayment
     * @throws \InvalidArgumentException
     */
    public function create($root, array $args)
    {
        // Preparar datos
        $data = [
            'invoice_id' => $args['invoiceId'],
            'user_id' => Auth::id(),
            'amount' => $args['amount'],
            'payment_date' => $args['paymentDate'],
            'payment_method' => $args['paymentMethod'] ?? null,
            'reference_number' => $args['referenceNumber'] ?? null,
            'notes' => $args['notes'] ?? null,
            'payment_support' => $args['paymentSupport'] ?? null,
        ];

        // Validar que el monto no exceda el saldo pendiente
        $invoice = \App\Models\Invoice\Invoice::findOrFail($args['invoiceId']);
        
        if ($data['amount'] > $invoice->real_outstanding_balance) {
            throw new \InvalidArgumentException(
                'El monto del abono (' . $data['amount'] . ') ' .
                'no puede ser mayor que el saldo pendiente (' . 
                $invoice->real_outstanding_balance . ')'
            );
        }

        return $this->service->save($data);
    }

    /**
     * Actualizar un abono existente
     *
     * @param mixed $root
     * @param array $args
     * @return \App\Models\Invoice\InvoicePayment
     * @throws \InvalidArgumentException
     */
    public function update($root, array $args)
    {
        $data = array_filter([
            'amount' => $args['amount'] ?? null,
            'payment_date' => $args['paymentDate'] ?? null,
            'payment_method' => $args['paymentMethod'] ?? null,
            'reference_number' => $args['referenceNumber'] ?? null,
            'notes' => $args['notes'] ?? null,
        ], fn($value) => $value !== null);

        // Si se actualiza el monto, validar
        if (isset($data['amount'])) {
            $payment = $this->service->getById($args['id']);
            $invoice = $payment->invoice;
            
            // Calcular nuevo saldo considerando el cambio
            $currentPaymentTotal = $invoice->payments()->sum('amount');
            $newPaymentTotal = $currentPaymentTotal - $payment->amount + $data['amount'];
            
            if ($newPaymentTotal > $invoice->total) {
                throw new \InvalidArgumentException(
                    'El nuevo monto causaría que los pagos totales excedan el total de la factura'
                );
            }
        }

        return $this->service->update($data, $args['id']);
    }

    /**
     * Eliminar un abono
     *
     * @param mixed $root
     * @param array $args
     * @return array
     * @throws \InvalidArgumentException
     */
    public function delete($root, array $args)
    {
        $payment = $this->service->getById($args['id']);
        $invoice = $payment->invoice;
        
        $this->service->deleteById($args['id']);
        
        // Recargar la factura actualizada
        $invoice->refresh();
        
        return [
            'success' => true,
            'message' => 'Abono eliminado correctamente',
            'invoice' => $invoice,
        ];
    }
}
```

---

## 📝 Ejemplos de Uso

### 1. Obtener todos los abonos de una factura

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
      email
    }
    createdAt
  }
}
```

**Variables:**
```json
{
  "invoiceId": "123"
}
```

### 2. Crear un abono parcial

```graphql
mutation CreatePayment($input: CreateInvoicePaymentInput!) {
  createInvoicePayment(input: $input) {
    id
    amount
    paymentDate
    paymentMethod
    referenceNumber
    invoice {
      id
      incrementId
      total
      totalPaid
      realOutstandingBalance
      status
      isFullyPaid
    }
  }
}
```

**Variables:**
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

### 3. Actualizar un abono

```graphql
mutation UpdatePayment($id: ID!, $input: UpdateInvoicePaymentInput!) {
  updateInvoicePayment(id: $id, input: $input) {
    id
    amount
    paymentDate
    paymentMethod
    notes
    updatedAt
  }
}
```

**Variables:**
```json
{
  "id": "45",
  "input": {
    "amount": 75000,
    "notes": "Monto corregido - Abono completo"
  }
}
```

### 4. Eliminar un abono

```graphql
mutation DeletePayment($id: ID!) {
  deleteInvoicePayment(id: $id) {
    success
    message
    invoice {
      id
      totalPaid
      realOutstandingBalance
      status
    }
  }
}
```

**Variables:**
```json
{
  "id": "45"
}
```

### 5. Obtener factura con todos sus abonos

```graphql
query GetInvoiceWithPayments($id: ID!) {
  invoice(id: $id) {
    id
    incrementId
    total
    totalPaid
    realOutstandingBalance
    status
    isFullyPaid
    
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
    }
  }
}
```

### 6. Listar abonos con paginación

```graphql
query ListPayments($page: Int!, $first: Int!) {
  invoicePayments(first: $first, page: $page, orderBy: [{field: "created_at", order: DESC}]) {
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
      invoice {
        incrementId
        customer {
          firstName
          lastName
        }
      }
      user {
        name
      }
    }
  }
}
```

---

## 🔐 Autenticación y Permisos

### Configurar Sanctum para GraphQL

**Archivo:** `config/lighthouse.php`

```php
'guards' => ['api'],
```

**Archivo:** `graphql/schema.graphql`

```graphql
extend type Query @guard(with: ["api"])
extend type Mutation @guard(with: ["api"])
```

### Usar directiva @can

Lighthouse ya incluye las validaciones con `@can`:

```graphql
invoicePayment(id: ID!): InvoicePayment 
  @find
  @can(ability: "viewInvoicePayment", find: "id")
```

Esto automáticamente verifica:
```php
$user->can('viewInvoicePayment', $invoicePayment)
```

---

## 🧪 Testing

### Test de GraphQL

**Archivo:** `tests/Feature/GraphQL/InvoicePaymentTest.php`

```php
<?php

namespace Tests\Feature\GraphQL;

use App\Models\Invoice\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->invoice = Invoice::factory()->create([
            'total' => 100000,
            'amount' => 0,
            'outstanding_balance' => 100000,
            'status' => 'unpaid',
        ]);
    }

    public function test_create_invoice_payment()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->graphQL('
            mutation CreatePayment($input: CreateInvoicePaymentInput!) {
                createInvoicePayment(input: $input) {
                    id
                    amount
                    invoice {
                        totalPaid
                        realOutstandingBalance
                    }
                }
            }
        ', [
            'input' => [
                'invoiceId' => $this->invoice->id,
                'amount' => 50000,
                'paymentDate' => now()->format('Y-m-d'),
                'paymentMethod' => 'CASH',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createInvoicePayment' => [
                    'amount' => 50000,
                    'invoice' => [
                        'totalPaid' => 50000,
                        'realOutstandingBalance' => 50000,
                    ],
                ],
            ],
        ]);
    }

    public function test_cannot_exceed_outstanding_balance()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->graphQL('
            mutation CreatePayment($input: CreateInvoicePaymentInput!) {
                createInvoicePayment(input: $input) {
                    id
                }
            }
        ', [
            'input' => [
                'invoiceId' => $this->invoice->id,
                'amount' => 150000, // Más del total
                'paymentDate' => now()->format('Y-m-d'),
            ],
        ]);

        $response->assertGraphQLErrorMessage('El monto del abono');
    }
}
```

---

## 🚀 Deployment

### 1. Generar cache del schema

```bash
php artisan lighthouse:cache
```

### 2. Optimizar en producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. GraphQL Playground

Accede a: `http://tu-dominio.com/graphql-playground`

---

## 📚 Recursos

- **Lighthouse PHP:** https://lighthouse-php.com/
- **GraphQL:** https://graphql.org/
- **Laravel Sanctum:** https://laravel.com/docs/sanctum

---

## 💡 Tips y Mejores Prácticas

1. **Usar DataLoaders:** Para optimizar N+1 queries
2. **Implementar Rate Limiting:** Proteger la API GraphQL
3. **Validar en Resolvers:** Además de las reglas del schema
4. **Usar Subscriptions:** Para notificaciones en tiempo real (opcional)
5. **Documentar con descripciones:** En el schema GraphQL

---

¡Listo para implementar! 🎉
