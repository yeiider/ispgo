# API de Facturas Manuales

## 📋 Descripción

Las **facturas manuales** permiten facturar conceptos diferentes a los servicios mensuales de internet, como:
- Instalaciones de fibra óptica
- Venta de equipos (routers, ONUs, etc.)
- Trabajos externos o técnicos
- Servicios adicionales puntuales
- Cualquier otro concepto que no sea parte de la suscripción mensual

### Características principales

✅ **Independientes de servicios**: No requieren `service_id`
✅ **Sin afectación de novedades**: No aplican reglas automáticas de novedades
✅ **Totales manuales**: Los valores son calculados desde los items y ajustes enviados
✅ **Modificables**: Se pueden actualizar antes de ser pagadas
✅ **Identificables**: Campo `invoice_type` permite distinguirlas de facturas de suscripción

---

## 🔧 Tipos de Factura

```graphql
enum InvoiceType {
    subscription  # Factura mensual de servicios (automática)
    manual        # Factura manual (instalaciones, otros)
    adjustment    # Ajustes/correcciones
}
```

---

## 📝 API GraphQL

### 1. Crear Factura Manual

#### Mutation

```graphql
mutation CreateManualInvoice($input: CreateManualInvoiceInput!) {
  createManualInvoice(input: $input) {
    success
    message
    invoice {
      id
      increment_id
      invoice_type
      customer_id
      customer_name
      issue_date
      due_date
      subtotal
      tax
      discount
      total
      outstanding_balance
      status
      items {
        id
        description
        quantity
        unit_price
        subtotal
      }
      adjustments {
        id
        kind
        amount
        label
      }
    }
    errors
  }
}
```

#### Variables

```json
{
  "input": {
    "customer_id": "123",
    "issue_date": "2026-02-19",
    "due_date": "2026-03-19",
    "items": [
      {
        "description": "Instalación de fibra óptica",
        "quantity": 1,
        "unit_price": 150000
      },
      {
        "description": "Router WiFi 6",
        "quantity": 1,
        "unit_price": 180000
      }
    ],
    "adjustments": [
      {
        "kind": "tax",
        "amount": 62700,
        "label": "IVA 19%"
      },
      {
        "kind": "discount",
        "amount": -20000,
        "label": "Descuento cliente frecuente"
      }
    ],
    "notes": "Instalación programada para el 20 de febrero",
    "status": "unpaid"
  }
}
```

#### Respuesta Exitosa

```json
{
  "data": {
    "createManualInvoice": {
      "success": true,
      "message": "Factura manual creada exitosamente",
      "invoice": {
        "id": "456",
        "increment_id": "0000000456",
        "invoice_type": "manual",
        "customer_id": "123",
        "customer_name": "Juan Pérez",
        "issue_date": "2026-02-19",
        "due_date": "2026-03-19",
        "subtotal": 310000,
        "tax": 62700,
        "discount": 20000,
        "total": 352700,
        "outstanding_balance": 352700,
        "status": "unpaid",
        "items": [
          {
            "id": "789",
            "description": "Instalación de fibra óptica",
            "quantity": 1,
            "unit_price": 150000,
            "subtotal": 150000
          },
          {
            "id": "790",
            "description": "Router WiFi 6",
            "quantity": 1,
            "unit_price": 180000,
            "subtotal": 180000
          }
        ],
        "adjustments": [
          {
            "id": "101",
            "kind": "tax",
            "amount": 62700,
            "label": "IVA 19%"
          },
          {
            "id": "102",
            "kind": "discount",
            "amount": -20000,
            "label": "Descuento cliente frecuente"
          }
        ]
      },
      "errors": null
    }
  }
}
```

---

### 2. Actualizar Factura Manual

**⚠️ Importante**: Solo se pueden actualizar facturas manuales que **NO** han sido pagadas.

#### Mutation

```graphql
mutation UpdateManualInvoice($input: UpdateManualInvoiceInput!) {
  updateManualInvoice(input: $input) {
    success
    message
    invoice {
      id
      increment_id
      total
      status
      items {
        description
        quantity
        unit_price
      }
    }
    errors
  }
}
```

#### Variables

```json
{
  "input": {
    "invoice_id": "456",
    "items": [
      {
        "description": "Instalación de fibra óptica",
        "quantity": 1,
        "unit_price": 180000
      },
      {
        "description": "Router WiFi 6 Premium",
        "quantity": 1,
        "unit_price": 250000
      }
    ],
    "adjustments": [
      {
        "kind": "tax",
        "amount": 81700,
        "label": "IVA 19%"
      }
    ],
    "notes": "Actualización de precios y equipos"
  }
}
```

---

### 3. Eliminar Factura Manual

**⚠️ Importante**: Solo se pueden eliminar facturas manuales que **NO** han sido pagadas.

#### Mutation

```graphql
mutation DeleteManualInvoice($invoiceId: ID!) {
  deleteManualInvoice(invoice_id: $invoiceId) {
    success
    message
    errors
  }
}
```

#### Variables

```json
{
  "invoiceId": "456"
}
```

#### Respuesta

```json
{
  "data": {
    "deleteManualInvoice": {
      "success": true,
      "message": "Factura manual eliminada exitosamente",
      "errors": null
    }
  }
}
```

---

### 4. Consultar Facturas Manuales

#### Query

```graphql
query GetManualInvoices($customerId: ID, $status: InvoiceStatus) {
  manualInvoices(customer_id: $customerId, status: $status, first: 20) {
    data {
      id
      increment_id
      invoice_type
      customer_name
      issue_date
      due_date
      total
      outstanding_balance
      status
      items {
        description
        quantity
        unit_price
        subtotal
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

#### Variables

```json
{
  "customerId": "123",
  "status": "unpaid"
}
```

---

## 📊 Estructura de Items

Los **items** representan los conceptos o productos a facturar:

```typescript
interface ManualInvoiceItemInput {
  description: string;      // Descripción del item
  quantity: number;          // Cantidad (mínimo 1)
  unit_price: number;        // Precio unitario sin IVA
  service_id?: string;       // Opcional: ID del servicio relacionado
  metadata?: JSON;           // Opcional: datos adicionales
}
```

**Ejemplo de cálculo**:
```
Item 1: Instalación × 1 = $150,000
Item 2: Router × 1     = $180,000
                       ----------
Subtotal Items:         $330,000
```

---

## 🔧 Estructura de Ajustes (Adjustments)

Los **ajustes** permiten agregar cargos adicionales, descuentos, impuestos o anulaciones:

```typescript
interface ManualInvoiceAdjustmentInput {
  kind: 'charge' | 'discount' | 'tax' | 'void';  // Tipo de ajuste
  amount: number;                                  // Monto del ajuste
  label: string;                                   // Etiqueta descriptiva
  source_type?: string;                            // Opcional
  source_id?: string;                              // Opcional
  metadata?: JSON;                                 // Opcional
}
```

### Tipos de Ajustes

| Tipo       | Descripción                       | Ejemplo                           |
|------------|-----------------------------------|-----------------------------------|
| `charge`   | Cargo adicional                   | Cargo por urgencia: +$50,000      |
| `discount` | Descuento (usar valor negativo)   | Descuento 10%: -$33,000           |
| `tax`      | Impuesto                          | IVA 19%: +$62,700                 |
| `void`     | Anulación/reducción               | Devolución parcial: -$10,000      |

**Ejemplo de cálculo completo**:
```
Subtotal Items:              $330,000
+ Cargo urgencia:             $50,000
- Descuento:                 -$20,000
                            ----------
Subtotal:                    $360,000
+ IVA 19%:                    $68,400
                            ----------
TOTAL:                       $428,400
```

---

## 💡 Casos de Uso Comunes

### 1. Instalación Simple

```json
{
  "customer_id": "123",
  "issue_date": "2026-02-19",
  "due_date": "2026-03-19",
  "items": [
    {
      "description": "Instalación de fibra óptica",
      "quantity": 1,
      "unit_price": 150000
    }
  ],
  "adjustments": [
    {
      "kind": "tax",
      "amount": 28500,
      "label": "IVA 19%"
    }
  ]
}
```

**Total**: $178,500

---

### 2. Venta de Equipos con Descuento

```json
{
  "customer_id": "456",
  "issue_date": "2026-02-19",
  "due_date": "2026-03-19",
  "items": [
    {
      "description": "Router WiFi 6",
      "quantity": 2,
      "unit_price": 180000
    },
    {
      "description": "Cable de red Cat6 (20m)",
      "quantity": 1,
      "unit_price": 35000
    }
  ],
  "adjustments": [
    {
      "kind": "discount",
      "amount": -39500,
      "label": "Descuento cliente mayorista 10%"
    },
    {
      "kind": "tax",
      "amount": 71155,
      "label": "IVA 19%"
    }
  ]
}
```

**Cálculo**:
- Items: $360,000 + $35,000 = $395,000
- Descuento 10%: -$39,500
- Subtotal: $355,500
- IVA 19%: +$67,545
- **Total**: $423,045

---

### 3. Instalación con Cargo de Urgencia

```json
{
  "customer_id": "789",
  "issue_date": "2026-02-19",
  "due_date": "2026-02-26",
  "items": [
    {
      "description": "Instalación express fibra óptica",
      "quantity": 1,
      "unit_price": 150000
    }
  ],
  "adjustments": [
    {
      "kind": "charge",
      "amount": 80000,
      "label": "Cargo por instalación en menos de 24 horas"
    },
    {
      "kind": "tax",
      "amount": 43700,
      "label": "IVA 19%"
    }
  ]
}
```

**Total**: $273,700

---

## ⚠️ Validaciones y Restricciones

### Al Crear
- ✅ `customer_id` debe existir en la tabla `customers`
- ✅ `issue_date` debe ser una fecha válida
- ✅ `due_date` debe ser igual o posterior a `issue_date`
- ✅ Debe incluir al menos **1 item**
- ✅ `quantity` debe ser mayor a 0
- ✅ `unit_price` debe ser mayor o igual a 0

### Al Actualizar
- ✅ La factura debe existir
- ✅ Debe ser de tipo `manual`
- ❌ **NO** se puede actualizar si ya fue pagada (`status = 'paid'`)

### Al Eliminar
- ✅ La factura debe existir
- ✅ Debe ser de tipo `manual`
- ❌ **NO** se puede eliminar si ya fue pagada (`status = 'paid'`)

---

## 🔐 Autenticación

Todas las operaciones requieren autenticación mediante token Bearer:

```http
Authorization: Bearer <tu_token_jwt>
```

---

## 🚨 Manejo de Errores

### Error: Cliente no encontrado

```json
{
  "data": {
    "createManualInvoice": {
      "success": false,
      "message": "Cliente no encontrado",
      "invoice": null,
      "errors": ["El cliente especificado no existe"]
    }
  }
}
```

### Error: Factura ya pagada

```json
{
  "data": {
    "updateManualInvoice": {
      "success": false,
      "message": "Error al actualizar la factura manual: No se puede actualizar una factura que ya ha sido pagada",
      "invoice": null,
      "errors": ["No se puede actualizar una factura que ya ha sido pagada"]
    }
  }
}
```

### Error: Tipo de factura incorrecto

```json
{
  "data": {
    "deleteManualInvoice": {
      "success": false,
      "message": "Error al eliminar la factura manual: Solo se pueden eliminar facturas de tipo manual",
      "invoice": null,
      "errors": ["Solo se pueden eliminar facturas de tipo manual"]
    }
  }
}
```

---

## 📌 Notas Importantes

1. **Campo `service_id` nullable**: Las facturas manuales no requieren un servicio asociado, por lo que este campo es opcional.

2. **No aplican novedades**: A diferencia de las facturas de suscripción, las facturas manuales no se ven afectadas por las novedades o reglas automáticas del sistema.

3. **Cálculo de totales**: Los totales se calculan automáticamente en el backend basándose en los items y adjustments enviados. El frontend debe enviar estos valores correctamente calculados.

4. **Identificación de tipo**: El campo `invoice_type = 'manual'` permite filtrar y distinguir estas facturas de las facturas mensuales automáticas.

5. **Modificación antes de pago**: Una vez que una factura es marcada como pagada, **no se puede modificar ni eliminar**.

6. **Router ID**: Si no se especifica `router_id`, se tomará automáticamente el router asociado al cliente.

---

## 🔄 Flujo de Trabajo Recomendado

1. **Crear factura manual** con items y ajustes
2. **Verificar totales** (el backend los calcula automáticamente)
3. **Actualizar si es necesario** (antes del pago)
4. **Registrar pago** usando el flujo normal de pagos de facturas
5. Una vez pagada, la factura queda **inmutable**

---

## 📚 Recursos Adicionales

- **Modelo**: `app/Models/Invoice/Invoice.php`
- **Mutation**: `app/GraphQL/Mutations/ManualInvoiceMutation.php`
- **Schema**: `graphql/invoice.schema.graphql`
- **Migración**: `database/migrations/2026_02_19_152205_add_invoice_type_to_invoices_table.php`

---

## 🎯 Ejemplo Completo Frontend (TypeScript)

```typescript
import { gql, useMutation } from '@apollo/client';

const CREATE_MANUAL_INVOICE = gql`
  mutation CreateManualInvoice($input: CreateManualInvoiceInput!) {
    createManualInvoice(input: $input) {
      success
      message
      invoice {
        id
        increment_id
        total
        status
      }
      errors
    }
  }
`;

function CreateInvoiceForm() {
  const [createInvoice, { loading, error }] = useMutation(CREATE_MANUAL_INVOICE);

  const handleSubmit = async (formData) => {
    try {
      const { data } = await createInvoice({
        variables: {
          input: {
            customer_id: formData.customerId,
            issue_date: formData.issueDate,
            due_date: formData.dueDate,
            items: formData.items.map(item => ({
              description: item.description,
              quantity: item.quantity,
              unit_price: item.unitPrice,
            })),
            adjustments: [
              {
                kind: 'tax',
                amount: calculateTax(formData.items),
                label: 'IVA 19%',
              },
            ],
            notes: formData.notes,
          },
        },
      });

      if (data.createManualInvoice.success) {
        console.log('Factura creada:', data.createManualInvoice.invoice);
        // Mostrar mensaje de éxito
      } else {
        console.error('Errores:', data.createManualInvoice.errors);
        // Mostrar errores
      }
    } catch (err) {
      console.error('Error:', err);
    }
  };

  const calculateTax = (items) => {
    const subtotal = items.reduce((sum, item) =>
      sum + (item.quantity * item.unitPrice), 0
    );
    return subtotal * 0.19;
  };

  // ... resto del componente
}
```

---

**Fecha de creación**: 2026-02-19
**Versión**: 1.0
**Autor**: Sistema ISPGO
