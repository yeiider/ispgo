# Guía de Uso del Reporte de Facturas

Esta guía describe cómo utilizar el nuevo endpoint de reportes de facturas expuesto vía GraphQL.

## Endpoint

**Query**: `invoiceReport`

Este endpoint permite obtener un resumen completo de las facturas, incluyendo totales monetarios, conteos por estado y datos agregados para gráficos.

## Argumentos

El query acepta los siguientes argumentos opcionales:

| Argumento | Tipo | Descripción | Default |
|---|---|---|---|
| `date_from` | `Date` (YYYY-MM-DD) | Fecha de inicio del reporte. | Inicio del mes actual |
| `date_to` | `Date` (YYYY-MM-DD) | Fecha de fin del reporte. | Fin del mes actual |
| `status` | `[String]` | Lista de estados para filtrar (ej: `["paid", "unpaid"]`). | Todos los estados |
| `payment_method` | `[String]` | Lista de métodos de pago para filtrar (ej: `["cash", "card"]`). | Todos los métodos |
| `chart_frequency` | `String` | Frecuencia de agrupación para el gráfico de ingresos (`daily`, `monthly`, `yearly`). | `daily` |

## Estructura de Respuesta

La respuesta (`InvoiceReport`) contiene tres secciones principales:

### 1. `summary` (Resumen)
Totales generales para el rango de fechas seleccionado.
- `total_invoices`: Cantidad total de facturas.
- `total_amount`: Monto total facturado.
- `total_paid`: Monto total pagado.
- `total_outstanding`: Saldo pendiente total.
- `total_discount`: Total de descuentos aplicados.
- `paid_count`, `unpaid_count`, `overdue_count`, `canceled_count`: Conteo de facturas por estado.

### 2. `charts` (Gráficos)
Datos listos para ser consumidos por librerías de gráficos.
- `revenue_over_time`: Ingresos agrupados por fecha (según `chart_frequency`).
- `status_distribution`: Distribución de montos y cantidades por estado.
- `payment_method_distribution`: Distribución de montos y cantidades por método de pago.

Cada punto de datos contiene:
- `label`: Etiqueta del dato (fecha, estado, o método de pago).
- `value`: Valor monetario asociado.
- `count`: Cantidad de facturas asociadas.

### 3. Filtros Aplicados
La respuesta también devuelve los filtros que se utilizaron (`date_from`, `date_to`, etc.) para confirmación en el frontend.

## Ejemplos de Consultas

### Reporte del Mes Actual (Default)
```graphql
query {
  invoiceReport {
    summary {
      total_invoices
      total_amount
      total_paid
      total_outstanding
    }
    charts {
      revenue_over_time {
        label
        value
      }
    }
  }
}
```

### Reporte Personalizado (Filtros y Gráficos)
Reporte de Enero 2024, solo facturas pagadas, agrupado mensualmente.

```graphql
query {
  invoiceReport(
    date_from: "2024-01-01"
    date_to: "2024-01-31"
    status: ["paid"]
    chart_frequency: "monthly"
  ) {
    summary {
      paid_count
      total_paid
    }
    charts {
      status_distribution {
        label
        value
        count
      }
      payment_method_distribution {
        label
        value
      }
    }
  }
}
```
