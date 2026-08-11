# API de Recaudo para Cajeros (Cashiers)

## Descripción General
Esta API está diseñada para proveer a los cajeros o usuarios responsables de un punto de recaudo toda la información pertinente a los cobros que ellos mismos han registrado en el sistema. Filtra automáticamente por el ID del usuario autenticado (`payment_registered_by`), por lo que el frontend no necesita enviar el ID del usuario, asegurando que cada cajero solo vea lo que él ha cobrado.

Esta API es ideal para integrarse en un "Punto de Venta" (POS) o "Dashboard del Cajero", permitiéndoles cuadrar su caja antes del cierre diario.

**Importante:** Para poder registrar pagos y usar estas APIs con normalidad, se recomienda que el usuario sea el responsable asignado a una caja registradora (`CashRegister`) en su sede.

## Endpoints Disponibles (GraphQL)

Se han añadido dos nuevos endpoints de tipo `Query` en el archivo `graphql/invoice.schema.graphql`.

### 1. `myCollectedInvoices` (Paginado)
Retorna una lista paginada de todas las facturas pagadas donde el usuario actual fue quien registró el pago.

**Filtros Disponibles:**
- `date`: `Date` (ej. "2023-10-25" - Filtra pagos exactos de un día).
- `date_from`: `Date` (Inicio de rango).
- `date_to`: `Date` (Fin de rango).
- `payment_method`: `String` (ej. "cash", "transfer" - Filtra por un método específico).

**Ejemplo de Petición:**
```graphql
query GetMyDailyInvoices {
  myCollectedInvoices(date: "2026-03-02", first: 15) {
    data {
      id
      increment_id
      customer_name
      amount
      payment_method
      payment_date
      additional_information
    }
    paginatorInfo {
      total
      currentPage
      lastPage
    }
  }
}
```

### 2. `myDailyCollectionReport` (Resumen/Tablero)
Retorna un sumario (summary) agrupando los totales de los pagos realizados por el cajero (solo toma en cuenta efectivo y transferencia, ignorando pagos en línea de los cuales el cajero no es responsable físico). Si no se envía fecha, por defecto usa la fecha de "hoy".

**Filtros Disponibles:**
- `date`: `Date` (Para ver el reporte de un día exacto).
- `date_from` y `date_to`: `Date` (Para ver el reporte de un rango de días).

**Campos que Retorna:**
- `date_from`
- `date_to`
- `total_cash` (Total cobrado en efectivo)
- `total_transfer` (Total cobrado por transferencia / datáfono manual)
- `total_collected` (Suma de cash + transfer)
- `total_invoices` (Cantidad de facturas procesadas en el reporte)

**Ejemplo de Petición (Dashboard Diario):**
```graphql
query GetMyCollectionSummary {
  myDailyCollectionReport(date: "2026-03-02") {
    date_from
    date_to
    total_cash
    total_transfer
    total_collected
    total_invoices
  }
}
```

## Caso de Uso Frontend (Dashboard de Cajero)
1. **Inicio del día:** El cajero abre su turno.
2. **Durante el día:** El cajero registra pagos en efectivo o mediante datáfono (transferencia). En el pago por datáfono, el frontend debe enviar la mutación `registerPayment` con el campo `transfer_reference` para guardar el número de voucher.
3. **Monitoreo (Punto POS):** En su pantalla de POS, el cajero puede tener un widget que consulte `myDailyCollectionReport` constantemente, mostrando un "Total Recaudado Hoy: $XXX (Efectivo: $X, Transferencia: $Y)".
4. **Auditoría:** Si el cajero nota una discrepancia o un cliente hace un reclamo de ese mismo día, el cajero usa una tabla alimentada por `myCollectedInvoices(date: "hoy")` para buscar y verificar el registro de pago.
5. **Cierre de Caja:** Al final del día, el monto total en `myDailyCollectionReport` debería ser exactamente el dinero en su caja (efectivo) y los vouchers impresos (transferencia), permitiéndole hacer un cierre de caja exitoso (usando la mutación `closeCashRegister`).
