# Sistema de Cajas Registradoras - Implementación GraphQL

## Descripción General

Este documento describe la implementación completa del sistema de cajas registradoras para gestionar cierres diarios y reportes de pagos de facturas por zona (router). El sistema permite:

1. Crear cajas por router (zonas)
2. Filtrar clientes por zonas (similar al modelo Invoice)
3. Realizar cierres de caja ejecutados por usuarios asignados
4. Generar reportes diarios y paginados
5. Informes detallados por método de pago
6. Procesamiento asíncrono con colas Redis

---

## Arquitectura del Sistema

### Modelos de Base de Datos

#### 1. CashRegister (Caja Registradora)
**Ubicación**: `app/Models/Finance/CashRegister.php`

Representa una caja asignada a un router específico.

**Campos principales**:
- `id`: ID único
- `name`: Nombre de la caja
- `router_id`: ID del router (zona) asociado
- `user_id`: Usuario asignado a la caja
- `initial_balance`: Balance inicial
- `current_balance`: Balance actual
- `status`: Estado (open/closed)
- `opened_at`: Fecha/hora de apertura
- `closed_at`: Fecha/hora de cierre
- `notes`: Notas adicionales
- `created_by`, `updated_by`: Usuarios de auditoría

**Relaciones**:
- `router()`: Pertenece a un Router
- `user()`: Usuario asignado
- `closures()`: Tiene muchos cierres de caja
- `invoices()`: Facturas asociadas

**Scopes disponibles**:
- `open()`: Solo cajas abiertas
- `closed()`: Solo cajas cerradas
- `byRouter($routerId)`: Filtrar por router
- `byUser($userId)`: Filtrar por usuario
- `byUserRouters()`: Filtrar por routers del usuario autenticado

#### 2. CashRegisterClosure (Cierre de Caja)
**Ubicación**: `app/Models/Finance/CashRegisterClosure.php`

Representa un cierre diario con toda la información de pagos.

**Campos principales**:
- `cash_register_id`: ID de la caja
- `user_id`: Usuario que realizó el cierre
- `closure_date`: Fecha del cierre
- `opening_balance`: Balance de apertura
- `closing_balance`: Balance de cierre
- `expected_balance`: Balance esperado
- `difference`: Diferencia entre esperado y real
- `total_cash`, `total_transfer`, `total_card`, `total_online`, `total_other`: Totales por método de pago
- `total_invoices`: Total de facturas procesadas
- `paid_invoices`: Facturas pagadas
- `total_collected`: Total recaudado
- `total_discounts`: Descuentos aplicados
- `total_adjustments`: Ajustes totales
- `payment_details`: JSON con detalles por método
- `invoice_summary`: JSON con resumen de facturas
- `status`: Estado (processing/completed/failed)
- `processed_at`: Fecha de procesamiento

**Relaciones**:
- `cashRegister()`: Pertenece a una caja
- `user()`: Usuario que realizó el cierre
- `invoices()`: Facturas incluidas en el cierre

---

## Migraciones

### 1. Extensión de cash_registers
**Archivo**: `database/migrations/2026_02_11_004227_add_router_and_user_fields_to_cash_registers_table.php`

Agrega campos para router, usuario, estado y auditoría.

### 2. Tabla cash_register_closures
**Archivo**: `database/migrations/2026_02_11_004230_create_cash_register_closures_table.php`

Crea la tabla completa de cierres con todos los campos necesarios.

**Ejecutar migraciones**:
```bash
php artisan migrate
```

---

## Procesamiento Asíncrono con Redis

### Job: ProcessCashRegisterClosure
**Ubicación**: `app/Jobs/ProcessCashRegisterClosure.php`

Este Job procesa el cierre de caja de forma asíncrona usando Redis.

**Características**:
- Cola: `cash-register-closures`
- Intentos: 3
- Timeout: 5 minutos
- Transaccional (rollback en caso de error)

**Proceso**:
1. Obtiene todas las facturas pagadas del día para la caja
2. Calcula totales por método de pago
3. Genera detalles de pago y resumen de facturas
4. Calcula diferencias entre esperado y real
5. Marca el cierre como completado
6. Cierra la caja y actualiza el balance

**Configuración de Redis** (en `.env`):
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Ejecutar worker**:
```bash
php artisan queue:work redis --queue=cash-register-closures
```

---

## API GraphQL

### Schema
**Ubicación**: `graphql/cash-registers.graphql`

### Tipos Principales

#### CashRegister
```graphql
type CashRegister {
    id: ID!
    name: String!
    router: Router
    user: User
    initialBalance: Float!
    currentBalance: Float!
    status: CashRegisterStatus!
    openedAt: DateTime
    closedAt: DateTime
    closures: [CashRegisterClosure!]!
    invoices: [Invoice!]!
}
```

#### CashRegisterClosure
```graphql
type CashRegisterClosure {
    id: ID!
    cashRegister: CashRegister!
    user: User!
    closureDate: Date!
    openingBalance: Float!
    closingBalance: Float!
    expectedBalance: Float!
    difference: Float!
    totalCash: Float!
    totalTransfer: Float!
    totalCard: Float!
    totalOnline: Float!
    totalOther: Float!
    totalInvoices: Int!
    paidInvoices: Int!
    totalCollected: Float!
    totalDiscounts: Float!
    paymentDetails: JSON
    invoiceSummary: JSON
    status: ClosureStatus!
}
```

---

## Queries (Consultas)

### 1. Obtener todas las cajas

```graphql
query {
  cashRegisters(
    routerId: 1
    status: OPEN
    onlyMyRouters: true
    orderBy: [{ column: "created_at", order: DESC }]
    first: 20
    page: 1
  ) {
    data {
      id
      name
      router {
        id
        name
      }
      user {
        id
        name
      }
      initialBalance
      currentBalance
      status
      openedAt
      closedAt
    }
    paginatorInfo {
      currentPage
      lastPage
      total
    }
  }
}
```

### 2. Obtener una caja específica

```graphql
query {
  cashRegister(id: 1) {
    id
    name
    router {
      id
      name
      code
    }
    user {
      id
      name
      email
    }
    initialBalance
    currentBalance
    status
    openedAt
    closures {
      id
      closureDate
      totalCollected
      status
    }
  }
}
```

### 3. Obtener cierres de caja

```graphql
query {
  cashRegisterClosures(
    cashRegisterId: 1
    status: COMPLETED
    dateFrom: "2026-02-01"
    dateTo: "2026-02-10"
    orderBy: [{ column: "closure_date", order: DESC }]
    first: 20
  ) {
    data {
      id
      closureDate
      openingBalance
      closingBalance
      expectedBalance
      difference
      totalCash
      totalTransfer
      totalCard
      totalOnline
      totalInvoices
      paidInvoices
      totalCollected
      totalDiscounts
      paymentDetails
      invoiceSummary
      status
      processedAt
    }
    paginatorInfo {
      currentPage
      lastPage
      total
    }
  }
}
```

### 4. Obtener reporte consolidado

```graphql
query {
  closureReport(
    dateFrom: "2026-02-01"
    dateTo: "2026-02-10"
    cashRegisterId: 1
  ) {
    totalClosures
    totalCollected
    totalDiscounts
    paymentMethodTotals {
      cash
      transfer
      card
      online
      other
    }
    totalInvoices
    averagePerClosure
    closuresWithDifferences
  }
}
```

---

## Mutations (Acciones)

### 1. Crear una caja

```graphql
mutation {
  createCashRegister(
    input: {
      name: "Caja Zona Norte"
      routerId: 1
      userId: 5
      initialBalance: 0
      notes: "Caja principal de la zona norte"
    }
  ) {
    id
    name
    router {
      id
      name
    }
    user {
      id
      name
    }
    initialBalance
    currentBalance
    status
  }
}
```

### 2. Actualizar una caja

```graphql
mutation {
  updateCashRegister(
    id: 1
    input: {
      name: "Caja Zona Norte - Principal"
      userId: 6
      currentBalance: 150000
      notes: "Balance actualizado"
    }
  ) {
    id
    name
    currentBalance
    notes
  }
}
```

### 3. Cerrar una caja (Acción principal)

```graphql
mutation {
  closeCashRegister(
    input: {
      cashRegisterId: 1
      closureDate: "2026-02-10"
      closingBalance: 250000
      notes: "Cierre del día 10 de febrero"
    }
  ) {
    success
    message
    closure {
      id
      status
      closureDate
    }
  }
}
```

**Nota**: Este mutation despacha un Job a Redis que procesa el cierre de forma asíncrona. El cierre quedará en estado `PROCESSING` y cambiará a `COMPLETED` cuando termine el procesamiento.

### 4. Abrir una caja cerrada

```graphql
mutation {
  openCashRegister(id: 1) {
    id
    name
    status
    openedAt
    initialBalance
    currentBalance
  }
}
```

### 5. Eliminar una caja

```graphql
mutation {
  deleteCashRegister(id: 1) {
    success
    message
  }
}
```

---

## Filtrado por Zonas (Routers)

El sistema implementa filtrado automático por zonas similar al modelo `Invoice`. Los usuarios solo pueden ver y gestionar cajas de los routers a los que están asignados.

### Implementación en CashRegister

El modelo `CashRegister` incluye un scope `byUserRouters()` que filtra automáticamente:

```php
public function scopeByUserRouters(Builder $query): Builder
{
    $user = Auth::user();
    $routerIds = $user->getRouterIds();

    if (empty($routerIds)) {
        return $query;
    }

    return $query->whereIn('router_id', $routerIds);
}
```

### Uso en GraphQL

```graphql
query {
  cashRegisters(onlyMyRouters: true) {
    data {
      id
      name
      router {
        id
        name
      }
    }
  }
}
```

---

## Informes por Método de Pago

El sistema genera informes detallados por cada método de pago disponible:

### Métodos soportados:
- `cash`: Efectivo
- `transfer`: Transferencia bancaria
- `card`: Tarjeta de crédito/débito
- `online`: Pago en línea
- `other`: Otros métodos (cheque, criptomonedas, etc.)

### Estructura de payment_details (JSON):

```json
{
  "cash": {
    "count": 15,
    "total": 125000,
    "invoices": ["0000000001", "0000000002", ...]
  },
  "transfer": {
    "count": 8,
    "total": 85000,
    "invoices": ["0000000003", "0000000004", ...]
  },
  "card": {
    "count": 5,
    "total": 40000,
    "invoices": ["0000000005", ...]
  }
}
```

### Estructura de invoice_summary (JSON):

```json
{
  "total_invoices": 28,
  "paid_invoices": 28,
  "total_amount": 250000,
  "total_paid": 250000,
  "total_discounts": 5000,
  "average_ticket": 8928.57,
  "customers": 25,
  "invoices_with_discount": 3,
  "invoices_with_adjustments": 2
}
```

---

## Integración con Facturas

### Campo en Invoice

El modelo `Invoice` incluye el campo `daily_box_id` que relaciona la factura con una caja:

```php
'daily_box_id' // en $fillable
```

### Relación en Invoice

```php
public function cashRegister()
{
    return $this->belongsTo(CashRegister::class, 'daily_box_id');
}
```

### Asignar factura a caja

Cuando se registra un pago, se puede asignar la caja:

```php
$invoice->applyPayment(
    $amount,
    'cash',
    [],
    'Pago registrado',
    $cashRegisterId  // ID de la caja
);
```

---

## Casos de Uso

### Caso 1: Crear y abrir una caja nueva

1. El administrador crea una caja para una zona:
```graphql
mutation {
  createCashRegister(
    input: {
      name: "Caja Zona Sur"
      routerId: 2
      userId: 7
      initialBalance: 0
    }
  ) {
    id
    status
  }
}
```

2. La caja se crea en estado `OPEN` automáticamente.

### Caso 2: Realizar cierre diario

1. El usuario asignado cierra la caja al final del día:
```graphql
mutation {
  closeCashRegister(
    input: {
      cashRegisterId: 1
      closureDate: "2026-02-10"
      closingBalance: 300000
      notes: "Cierre del día"
    }
  ) {
    success
    message
  }
}
```

2. El sistema despacha el Job a Redis para procesamiento asíncrono.

3. El Job:
   - Obtiene todas las facturas pagadas del día
   - Calcula totales por método de pago
   - Genera el resumen detallado
   - Marca el cierre como completado

4. Consultar el resultado:
```graphql
query {
  cashRegisterClosure(id: 1) {
    status
    totalCollected
    difference
    paymentDetails
  }
}
```

### Caso 3: Generar reporte mensual

```graphql
query {
  closureReport(
    dateFrom: "2026-02-01"
    dateTo: "2026-02-28"
  ) {
    totalClosures
    totalCollected
    paymentMethodTotals {
      cash
      transfer
      card
      online
    }
    averagePerClosure
  }
}
```

---

## Manejo de Descuentos y Novedades

El sistema considera automáticamente:

1. **Descuentos en facturas** (`discount` field en Invoice)
2. **Ajustes de factura** (InvoiceAdjustment)
3. **Novedades de facturación** (BillingNovedad)

Estos se reflejan en:
- `total_discounts`: Suma de descuentos
- `total_adjustments`: Suma de ajustes
- `invoice_summary`: Detalles completos

---

## Seguridad y Permisos

### Validaciones implementadas:

1. **Filtrado por Router**: Los usuarios solo ven cajas de sus routers asignados
2. **Verificación de permisos**: Antes de cada acción se valida el acceso
3. **Guard en GraphQL**: Todas las queries y mutations requieren autenticación
4. **Auditoría**: Se registra quién crea y modifica cada registro

### Middleware sugerido:

```php
// En routes o configuración de GraphQL
'guard' => 'sanctum',
'permission' => ['manage-cash-registers']
```

---

## Monitoreo y Logs

El Job registra eventos importantes:

```php
Log::info('Cierre de caja procesado exitosamente', [
    'closure_id' => $closure->id,
    'total_collected' => $totalCollected,
]);

Log::error('Error al procesar cierre de caja', [
    'cash_register_id' => $this->cashRegisterId,
    'error' => $e->getMessage(),
]);
```

### Consultar logs:
```bash
tail -f storage/logs/laravel.log | grep "cierre de caja"
```

---

## Pruebas y Depuración

### Verificar estado de colas:

```bash
php artisan queue:work redis --queue=cash-register-closures --once
```

### Limpiar colas fallidas:

```bash
php artisan queue:failed
php artisan queue:retry all
```

### Testing con GraphQL Playground:

Acceder a: `http://tu-dominio/graphql-playground`

---

## Mejoras Futuras

1. **Notificaciones**: Enviar notificación al usuario cuando termine el cierre
2. **Exportación**: Permitir exportar reportes a PDF/Excel
3. **Reconciliación**: Herramienta para corregir diferencias
4. **Dashboard**: Vista visual de estadísticas de cajas
5. **Cierres automáticos**: Programar cierres automáticos diarios
6. **Multi-caja**: Permitir que un usuario maneje múltiples cajas

---

## Soporte

Para preguntas o problemas:
- Revisar logs en `storage/logs/`
- Verificar configuración de Redis
- Consultar estado de workers: `php artisan queue:monitor`

---

## Resumen de Archivos Creados

### Migraciones:
- `database/migrations/2026_02_11_004227_add_router_and_user_fields_to_cash_registers_table.php`
- `database/migrations/2026_02_11_004230_create_cash_register_closures_table.php`

### Modelos:
- `app/Models/Finance/CashRegister.php`
- `app/Models/Finance/CashRegisterClosure.php`

### Jobs:
- `app/Jobs/ProcessCashRegisterClosure.php`

### GraphQL:
- `graphql/cash-registers.graphql`
- `app/GraphQL/Queries/CashRegisterQueries.php`
- `app/GraphQL/Mutations/CashRegisterMutations.php`

### Documentación:
- `docs/cash-registers-implementation.md` (este archivo)
