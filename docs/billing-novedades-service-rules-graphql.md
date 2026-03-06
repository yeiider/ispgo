# Documentación API GraphQL - Novedades de Facturación y Reglas de Servicio

Esta documentación describe los casos de uso de la API GraphQL para la gestión de novedades de facturación (BillingNovedad) y reglas de servicio (ServiceRule).

## Índice

1. [Conceptos](#conceptos)
2. [Tipos de Datos](#tipos-de-datos)
3. [Queries](#queries)
4. [Mutaciones](#mutaciones)
5. [Casos de Uso](#casos-de-uso)
6. [Ejemplos Prácticos](#ejemplos-prácticos)

---

## Conceptos

### Modelo de Datos

El sistema de facturación maneja dos entidades principales relacionadas con los servicios:

- **ServiceRule (Regla de Servicio)**: Define descuentos o beneficios aplicables a un servicio durante un número determinado de ciclos de facturación.
- **BillingNovedad (Novedad de Facturación)**: Representa ajustes que se aplican en la facturación como cargos adicionales, descuentos, prorrateos, moras, compensaciones, etc.

```
┌─────────────┐       ┌───────────────────┐       ┌─────────────────────┐
│   Service   │──────▶│   ServiceRule     │       │   BillingNovedad    │
│             │ 1   * │                   │       │                     │
│ - id        │       │ - id              │       │ - id                │
│ - plan_id   │       │ - service_id      │       │ - service_id        │
│ - status    │       │ - type            │       │ - customer_id       │
│ ...         │       │ - value           │       │ - type              │
└─────────────┘       │ - cycles          │       │ - amount            │
      │               │ - cycles_used     │       │ - applied           │
      │               │ - starts_at       │       │ - effective_period  │
      │               └───────────────────┘       │ - rule (JSON)       │
      │                                           └─────────────────────┘
      │                                                    ▲
      └────────────────────────────────────────────────────┘
                              1   *
```

### Tipos de ServiceRule

| Tipo | Descripción | Campo `value` |
|------|-------------|---------------|
| `percentage` | Descuento porcentual | Porcentaje (ej: 10 = 10%) |
| `fixed` | Descuento de monto fijo | Monto en moneda local |
| `free_month` | Mes gratis | No aplica (puede ser null) |

### Tipos de BillingNovedad

| Tipo | Descripción | Monto |
|------|-------------|-------|
| `saldo_favor` | Saldo a favor del cliente | Manual (positivo) |
| `cargo_adicional` | Cargo adicional | Manual (positivo) |
| `nota_credito` | Nota de crédito | Manual (negativo) |
| `prorrateo_inicial` | Prorrateo al activar servicio | Calculado automáticamente |
| `prorrateo_cancelacion` | Prorrateo al cancelar | Calculado automáticamente |
| `descuento_promocional` | Descuento por promoción | Calculado según regla |
| `cargo_reconexion` | Cargo por reconexión | Calculado según regla |
| `mora` | Intereses por mora | Calculado según regla |
| `compensacion` | Compensación por fallas | Manual o calculado |
| `exceso_consumo` | Cargo por exceso de datos | Calculado según regla |
| `impuesto` | Impuestos adicionales | Calculado según regla |
| `product_delivery` | Entrega de producto | Calculado según productos |
| `cambio_plan` | Ajuste por cambio de plan | Calculado automáticamente |

---

## Tipos de Datos

### ServiceRule

```graphql
type ServiceRule {
    id: ID!
    service_id: ID!
    type: String!           # percentage, fixed, free_month
    value: Float            # Valor según el tipo
    cycles: Int!            # Total de ciclos
    cycles_used: Int!       # Ciclos ya usados
    starts_at: DateTime     # Fecha de inicio
    is_active: Boolean      # cycles_used < cycles
    created_at: DateTime!
    updated_at: DateTime!
    service: Service!
}
```

### BillingNovedad

```graphql
type BillingNovedad {
    id: ID!
    service_id: ID!
    customer_id: ID
    invoice_id: ID
    type: String!           # Tipo de novedad
    amount: Float!          # Monto (+cargo, -descuento)
    description: String
    rule: JSON              # Parámetros adicionales
    effective_period: Date  # Periodo (YYYY-MM-01)
    applied: Boolean!       # Ya aplicada a factura
    product_lines: JSON     # Para entregas de producto
    quantity: Int
    unit_price: Float
    created_by: ID
    created_at: DateTime!
    updated_at: DateTime!
    service: Service
    customer: Customer
    invoice: Invoice
    creator: User
}
```

---

## Queries

### 1. Listar Reglas de Servicio

```graphql
query ListarReglasServicio(
    $service_id: ID
    $type: String
    $active_only: Boolean
    $first: Int
    $page: Int
) {
    serviceRules(
        service_id: $service_id
        type: $type
        active_only: $active_only
        first: $first
        page: $page
    ) {
        data {
            id
            service_id
            type
            value
            cycles
            cycles_used
            starts_at
            created_at
            service {
                id
                service_ip
                customer {
                    id
                    first_name
                    last_name
                }
            }
        }
        paginatorInfo {
            total
            currentPage
            lastPage
            hasMorePages
            perPage
        }
    }
}
```

**Variables:**
```json
{
    "service_id": "123",
    "type": "percentage",
    "active_only": true,
    "first": 15,
    "page": 1
}
```

### 2. Obtener Regla de Servicio por ID

```graphql
query ObtenerReglaServicio($id: ID!) {
    serviceRule(id: $id) {
        id
        service_id
        type
        value
        cycles
        cycles_used
        starts_at
        created_at
        updated_at
        service {
            id
            service_ip
            plan {
                id
                name
                monthly_price
            }
        }
    }
}
```

**Variables:**
```json
{
    "id": "1"
}
```

### 3. Reglas Activas de un Servicio

```graphql
query ReglasActivasServicio($service_id: ID!) {
    activeServiceRules(service_id: $service_id) {
        id
        type
        value
        cycles
        cycles_used
        starts_at
    }
}
```

**Variables:**
```json
{
    "service_id": "123"
}
```

### 4. Listar Novedades de Facturación

```graphql
query ListarNovedades(
    $service_id: ID
    $customer_id: ID
    $type: String
    $applied: Boolean
    $effective_period: Date
    $first: Int
    $page: Int
) {
    billingNovedades(
        service_id: $service_id
        customer_id: $customer_id
        type: $type
        applied: $applied
        effective_period: $effective_period
        first: $first
        page: $page
    ) {
        data {
            id
            type
            amount
            description
            applied
            effective_period
            created_at
            service {
                id
                service_ip
            }
            customer {
                id
                first_name
                last_name
            }
            invoice {
                id
                increment_id
            }
            creator {
                id
                name
            }
        }
        paginatorInfo {
            total
            currentPage
            lastPage
            hasMorePages
        }
    }
}
```

**Variables:**
```json
{
    "service_id": "123",
    "applied": false,
    "first": 15,
    "page": 1
}
```

### 5. Obtener Novedad por ID

```graphql
query ObtenerNovedad($id: ID!) {
    billingNovedad(id: $id) {
        id
        type
        amount
        description
        rule
        effective_period
        applied
        product_lines
        quantity
        unit_price
        created_at
        service {
            id
            service_ip
            plan {
                name
                monthly_price
            }
        }
        customer {
            id
            first_name
            last_name
        }
        invoice {
            id
            increment_id
            total
        }
    }
}
```

### 6. Novedades Pendientes de un Servicio

```graphql
query NovedadesPendientes($service_id: ID!) {
    pendingNovedades(service_id: $service_id) {
        id
        type
        amount
        description
        effective_period
        created_at
    }
}
```

### 7. Novedades por Periodo

```graphql
query NovedadesPorPeriodo($service_id: ID!, $effective_period: Date!) {
    novedadesByPeriod(
        service_id: $service_id
        effective_period: $effective_period
    ) {
        id
        type
        amount
        description
        applied
        created_at
    }
}
```

**Variables:**
```json
{
    "service_id": "123",
    "effective_period": "2025-01-01"
}
```

### 8. Consultar Servicio con sus Reglas y Novedades

```graphql
query ServicioConReglasYNovedades($id: ID!) {
    service(id: $id) {
        id
        service_ip
        service_status
        plan {
            name
            monthly_price
        }
        customer {
            first_name
            last_name
        }
        rules {
            id
            type
            value
            cycles
            cycles_used
        }
        billingNovedades {
            id
            type
            amount
            applied
            effective_period
        }
    }
}
```

---

## Mutaciones

### 1. Crear Regla de Servicio

```graphql
mutation CrearReglaServicio($input: CreateServiceRuleInput!) {
    createServiceRule(input: $input) {
        id
        service_id
        type
        value
        cycles
        cycles_used
        starts_at
        created_at
        service {
            id
            service_ip
        }
    }
}
```

**Variables (Descuento porcentual):**
```json
{
    "input": {
        "service_id": "123",
        "type": "percentage",
        "value": 15,
        "cycles": 6,
        "starts_at": "2025-01-01T00:00:00Z"
    }
}
```

**Variables (Descuento fijo):**
```json
{
    "input": {
        "service_id": "123",
        "type": "fixed",
        "value": 10000,
        "cycles": 3
    }
}
```

**Variables (Mes gratis):**
```json
{
    "input": {
        "service_id": "123",
        "type": "free_month",
        "cycles": 1
    }
}
```

### 2. Actualizar Regla de Servicio

```graphql
mutation ActualizarReglaServicio($id: ID!, $input: UpdateServiceRuleInput!) {
    updateServiceRule(id: $id, input: $input) {
        id
        type
        value
        cycles
        cycles_used
        starts_at
    }
}
```

**Variables:**
```json
{
    "id": "1",
    "input": {
        "value": 20,
        "cycles": 12
    }
}
```

### 3. Eliminar Regla de Servicio

```graphql
mutation EliminarReglaServicio($id: ID!) {
    deleteServiceRule(id: $id) {
        success
        message
    }
}
```

### 4. Reiniciar Ciclos de una Regla

```graphql
mutation ReiniciarCiclosRegla($id: ID!) {
    resetServiceRuleCycles(id: $id) {
        id
        cycles
        cycles_used
    }
}
```

### 5. Crear Novedad de Facturación

```graphql
mutation CrearNovedad($input: CreateBillingNovedadInput!) {
    createBillingNovedad(input: $input) {
        id
        type
        amount
        description
        effective_period
        applied
        created_at
        service {
            id
            service_ip
        }
        customer {
            id
            first_name
            last_name
        }
    }
}
```

**Variables (Cargo adicional):**
```json
{
    "input": {
        "service_id": "123",
        "type": "cargo_adicional",
        "amount": 25000,
        "description": "Cargo por instalación de equipo adicional",
        "effective_period": "2025-01-01"
    }
}
```

**Variables (Saldo a favor):**
```json
{
    "input": {
        "service_id": "123",
        "type": "saldo_favor",
        "amount": 15000,
        "description": "Saldo por pago anticipado",
        "effective_period": "2025-01-01"
    }
}
```

**Variables (Mora con parámetros):**
```json
{
    "input": {
        "service_id": "123",
        "type": "mora",
        "effective_period": "2025-01-01",
        "description": "Intereses por mora",
        "rule": {
            "mora_type": "percentage",
            "mora_value": 2.5,
            "pending_amount": 150000,
            "max_amount": 50000
        }
    }
}
```

**Variables (Compensación por falla):**
```json
{
    "input": {
        "service_id": "123",
        "type": "compensacion",
        "amount": -30000,
        "description": "Compensación por interrupción del servicio",
        "effective_period": "2025-01-01",
        "rule": {
            "compensation_type": "days",
            "outage_start": "2025-01-05",
            "outage_end": "2025-01-08",
            "compensation_factor": 1.5
        }
    }
}
```

**Variables (Descuento promocional):**
```json
{
    "input": {
        "service_id": "123",
        "type": "descuento_promocional",
        "effective_period": "2025-01-01",
        "description": "Promoción aniversario",
        "rule": {
            "percent": 20,
            "cycles_max": 3
        }
    }
}
```

### 6. Actualizar Novedad de Facturación

> **Nota:** Solo novedades NO aplicadas pueden ser modificadas.

```graphql
mutation ActualizarNovedad($id: ID!, $input: UpdateBillingNovedadInput!) {
    updateBillingNovedad(id: $id, input: $input) {
        id
        type
        amount
        description
        effective_period
        rule
    }
}
```

**Variables:**
```json
{
    "id": "1",
    "input": {
        "amount": 30000,
        "description": "Cargo actualizado por instalación"
    }
}
```

### 7. Eliminar Novedad de Facturación

> **Nota:** Solo novedades NO aplicadas pueden ser eliminadas.

```graphql
mutation EliminarNovedad($id: ID!) {
    deleteBillingNovedad(id: $id) {
        success
        message
    }
}
```

### 8. Marcar Novedad como Aplicada

```graphql
mutation MarcarNovedadAplicada($id: ID!, $invoice_id: ID!) {
    markNovedadAsApplied(id: $id, invoice_id: $invoice_id) {
        id
        applied
        invoice {
            id
            increment_id
        }
    }
}
```

**Variables:**
```json
{
    "id": "1",
    "invoice_id": "456"
}
```

---

## Casos de Uso

### Caso 1: Aplicar descuento por promoción a un cliente nuevo

1. **Crear regla de descuento:**
```graphql
mutation {
    createServiceRule(input: {
        service_id: "123"
        type: "percentage"
        value: 20
        cycles: 3
        starts_at: "2025-01-01T00:00:00Z"
    }) {
        id
        type
        value
        cycles
    }
}
```

### Caso 2: Registrar cargo por reconexión

1. **Crear novedad de cargo:**
```graphql
mutation {
    createBillingNovedad(input: {
        service_id: "123"
        type: "cargo_reconexion"
        effective_period: "2025-01-01"
        description: "Cargo por reconexión después de suspensión por mora"
        rule: {
            charge_type: "fixed"
            charge_value: 25000
        }
    }) {
        id
        amount
        type
    }
}
```

### Caso 3: Consultar todas las novedades pendientes antes de facturar

```graphql
query {
    pendingNovedades(service_id: "123") {
        id
        type
        amount
        description
        effective_period
    }
}
```

### Caso 4: Verificar reglas activas de un servicio

```graphql
query {
    activeServiceRules(service_id: "123") {
        id
        type
        value
        cycles
        cycles_used
    }
}
```

### Caso 5: Ver historial completo de un servicio

```graphql
query {
    service(id: "123") {
        id
        service_ip
        service_status
        plan {
            name
            monthly_price
        }
        rules {
            id
            type
            value
            cycles
            cycles_used
        }
        billingNovedades {
            id
            type
            amount
            description
            applied
            effective_period
            invoice {
                increment_id
            }
        }
    }
}
```

---

## Ejemplos Prácticos

### Flujo completo: Dar mes gratis a cliente

```graphql
# Paso 1: Crear regla de mes gratis
mutation {
    createServiceRule(input: {
        service_id: "123"
        type: "free_month"
        cycles: 1
    }) {
        id
    }
}

# Paso 2: Verificar regla creada
query {
    activeServiceRules(service_id: "123") {
        id
        type
        cycles
        cycles_used
    }
}
```

### Flujo completo: Gestionar compensación por interrupción

```graphql
# Paso 1: Crear novedad de compensación
mutation {
    createBillingNovedad(input: {
        service_id: "123"
        type: "compensacion"
        amount: -15000
        description: "Compensación por 3 días sin servicio"
        effective_period: "2025-01-01"
        rule: {
            compensation_type: "days"
            outage_start: "2025-01-10"
            outage_end: "2025-01-13"
        }
    }) {
        id
        amount
    }
}

# Paso 2: Verificar que la novedad está pendiente
query {
    pendingNovedades(service_id: "123") {
        id
        type
        amount
        description
    }
}

# Paso 3 (automático): La novedad se aplica al generar factura
# O manualmente:
mutation {
    markNovedadAsApplied(id: "1", invoice_id: "789") {
        id
        applied
    }
}
```

---

## Notas Importantes

1. **Novedades Aplicadas:** Una vez que una novedad se marca como aplicada (asociada a una factura), no puede ser modificada ni eliminada.

2. **Cálculo Automático:** Algunos tipos de novedades calculan su monto automáticamente basándose en los parámetros del campo `rule` y el plan del servicio.

3. **Periodo Efectivo:** El campo `effective_period` debe ser el primer día del mes (YYYY-MM-01) para el periodo de facturación correspondiente.

4. **Ciclos de Reglas:** El sistema incrementa automáticamente `cycles_used` cada vez que se aplica una regla en el ciclo de facturación.

5. **Relaciones en Service:** Al consultar un servicio, puedes incluir tanto `rules` como `billingNovedades` para obtener toda la información de facturación relacionada.

---

## Errores Comunes

| Error | Causa | Solución |
|-------|-------|----------|
| "No se puede modificar una novedad aplicada" | Intentar actualizar novedad con `applied: true` | Solo modificar novedades pendientes |
| "No se puede eliminar una novedad aplicada" | Intentar eliminar novedad asociada a factura | Crear nota de crédito en su lugar |
| "Esta novedad ya ha sido aplicada" | Llamar a `markNovedadAsApplied` en novedad ya aplicada | Verificar estado antes de marcar |
| "Service not found" | ID de servicio no existe | Verificar ID del servicio |

---

## Headers Requeridos

```
Authorization: Bearer {token}
Content-Type: application/json
```

## Endpoint

```
POST /graphql
```
