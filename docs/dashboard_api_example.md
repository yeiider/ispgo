# Dashboard API Integration Guide

Este documento describe cómo construir los datos necesarios para el Dashboard principal utilizando la API GraphQL. Se recomienda agrupar las consultas en una sola petición para optimizar el rendimiento.

## 1. Estructura de la Consulta (Query)

Para obtener un resumen completo (Financiero, Clientes, Servicios, Soporte y Red), sugerimos la siguiente consulta unificada.

### Query GraphQL

```graphql
query DashboardOverview(
    $dateFrom: Date!,
    $dateTo: Date!,
    $statusFilter: [String!],
    $chartFreq: String
) {
    # 1. Resumen Financiero y Gráficos (Facturación)
    # Revisa graphql/reports.graphql
    invoiceReport(
        date_from: $dateFrom,
        date_to: $dateTo,
        status: $statusFilter,
        chart_frequency: $chartFreq 
    ) {
        summary {
            total_invoices
            total_amount
            total_paid
            total_outstanding
            overdue_count
        }
        charts {
            revenue_over_time {
                label
                value
            }
            status_distribution {
                label
                value
                count
            }
        }
    }

    # 2. Clientes Activos (Total)
    # Se usa paginatorInfo para obtener el conteo total sin traer toda la data
    activeCustomers: customers(customer_status: "active", first: 1) {
        paginatorInfo {
            total
        }
    }

    # 3. Servicios Activos (Total)
    activeServices: services(service_status: "active", first: 1) {
        paginatorInfo {
            total
        }
    }

    # 4. Tickets Recientes (Soporte)
    # Traemos los 5 tickets abiertos más recientes
    recentTickets: tickets(
        status: "open", 
        first: 5, 
        page: 1
    ) {
        data {
            id
            title
            priority
            status
            created_at
            customer {
                first_name
                last_name
            }
        }
    }

    # 5. Estado de la Red (SmartOLT)
    # Revisa graphql/smartolt.graphql
    networkStatus: smartOltOltsUptime {
        olt_name
        uptime
        env_temp
    }
}
```

## 2. Variables de Ejemplo

```json
{
    "dateFrom": "2023-10-01",
    "dateTo": "2023-10-31",
    "statusFilter": ["paid", "unpaid", "overdue"],
    "chartFreq": "daily"
}
```

## 3. Ejemplo de Respuesta JSON

Esta es la estructura JSON que recibirá el Frontend para alimentar los widgets del dashboard.

```json
{
  "data": {
    "invoiceReport": {
      "summary": {
        "total_invoices": 150,
        "total_amount": 45000.50,
        "total_paid": 30000.00,
        "total_outstanding": 15000.50,
        "overdue_count": 12
      },
      "charts": {
        "revenue_over_time": [
          {
            "label": "2023-10-01",
            "value": 1200.00
          },
          {
            "label": "2023-10-02",
            "value": 1500.50
          }
        ],
        "status_distribution": [
          {
            "label": "Pagadas",
            "value": 30000.00,
            "count": 100
          },
          {
            "label": "Pendientes",
            "value": 10000.00,
            "count": 30
          },
          {
            "label": "Vencidas",
            "value": 5000.50,
            "count": 20
          }
        ]
      }
    },
    "activeCustomers": {
      "paginatorInfo": {
        "total": 1250
      }
    },
    "activeServices": {
      "paginatorInfo": {
        "total": 1340
      }
    },
    "recentTickets": {
      "data": [
        {
          "id": "1024",
          "title": "Sin conexión a internet",
          "priority": "high",
          "status": "open",
          "created_at": "2023-10-25 10:30:00",
          "customer": {
            "first_name": "Juan",
            "last_name": "Pérez"
          }
        },
        {
          "id": "1023",
          "title": "Lentitud en servicio",
          "priority": "medium",
          "status": "open",
          "created_at": "2023-10-25 09:15:00",
          "customer": {
            "first_name": "Maria",
            "last_name": "Gomez"
          }
        }
      ]
    },
    "networkStatus": [
      {
        "olt_name": "OLT-Norte",
        "uptime": "15d 4h 20m",
        "env_temp": "45C"
      },
      {
        "olt_name": "OLT-Sur",
        "uptime": "30d 12h 05m",
        "env_temp": "42C"
      }
    ]
  }
}
```

## 4. Notas de Implementación

- **Autenticación**: Recuerda que todas estas consultas requieren que el usuario esté autenticado y tenga los permisos necesarios (ej. token Bearer en el header).
- **Rendimiento**: La consulta de `invoiceReport` realiza cálculos agregados. Si el rango de fechas es muy amplio, podría demorar unos segundos. Considera cachear esta información o cargarla de forma diferida (lazy loading) si es necesario.
- **Paginación**: Para los contadores de `activeCustomers` y `activeServices`, solicitamos `first: 1` solo para obtener el `paginatorInfo.total` de la forma más ligera posible.
