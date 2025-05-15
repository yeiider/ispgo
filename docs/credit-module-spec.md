# Módulo de Créditos a Clientes
*(Especificación para desarrollador – Laravel 11 + Nova)*

---

## 1. Objetivo general
Habilitar a **ispgo** para vender productos a crédito, llevar el estado de cuenta de cada **Customer**, registrar pagos y gestionar mora, todo administrado desde **Laravel Nova** y respaldado por procesos automáticos (listeners, jobs cron).

---

## 2. Nuevos modelos y relaciones

| Modelo | Relaciones clave | Campos principales |
|--------|------------------|--------------------|
| `CreditAccount` | `belongsTo Customer`  •  `hasMany CreditInstallment`  •  `hasMany CreditPayment` | `principal decimal(15,2)`<br>`interest_rate decimal(5,2)` (% anual)<br>`grace_period_days int`<br>`status enum(active, in_grace, overdue, closed)` |
| `CreditAccountProduct` (pivot) | `belongsTo CreditAccount`  •  `belongsTo Product` | `quantity` • `unit_price` • `subtotal` |
| `CreditInstallment` | `belongsTo CreditAccount` | `due_date date` • `amount_due decimal` • `interest_portion decimal` • `principal_portion decimal` • `status enum(pending, paid, overdue)` |
| `CreditPayment` | `belongsTo CreditAccount` • `morphMany AccountEntry` | `paid_at datetime` • `amount decimal` • `method string` • `reference string` • `notes text` |
| `AccountEntry` | `morphTo (creditable)` (`CreditInstallment` &#124; `CreditPayment`) | `entry_type enum(debit, credit)` • `amount decimal` • `balance_after decimal` |

> **Nota:** `AccountEntry` permite generar rápidamente estados de cuenta (“ledger”).

---

## 3. Migraciones

1. **credit_accounts** – incluye FK `customer_id`, índices por `status`.
2. **credit_account_product** – FK `credit_account_id`, `product_id`.
3. **credit_installments** – FK `credit_account_id`, índice por `due_date`.
4. **credit_payments** – FK `credit_account_id`.
5. **account_entries** – morphs `creditable_id / creditable_type`.

```bash
php artisan make:migration create_credit_accounts_table
# …repite para cada tabla
```

Usa `foreignId()->constrained()` y `decimal(15,2)` para precisión monetaria.

---

## 4. Servicios de dominio (`app/Services/Credit`)

| Servicio | Responsabilidad principal |
|----------|---------------------------|
| **CreditAccountService::open($data)** | Valida productos, calcula `principal`, genera `installments` según términos (meses, interés, gracia). |
| **InstallmentScheduler** | Produce plan de amortización (sistema francés o cuotas fijas). |
| **PaymentService::apply(CreditAccount $account, CreditPayment $payment)** | Imputa el pago a cuotas pendientes (FIFO), registra `AccountEntry`, actualiza estados. |
| **PenaltyService::applyOverduePenalties()** | Calcula y aplica interés/mora diaria a cuotas _overdue_. |

---

## 5. Eventos & listeners

| Evento | Listener | Acción |
|--------|----------|--------|
| `CreditOpened` | `GenerateInstallments` | Construye las cuotas al abrir crédito. |
| `PaymentReceived` | `UpdateLedger` | Actualiza `AccountEntry`, recalcula saldo. |
| `InstallmentOverdue` | `MarkAccountOverdue` | Cambia estado y dispara notificaciones. |

Usa `dispatchAfterResponse()` para mantener la UX de Nova ágil al registrar pagos.

---

## 6. Jobs programados

```php
// App\Console\Kernel.php
$schedule->command('credits:check-overdues')->dailyAt('03:00');
$schedule->command('credits:apply-penalties')->dailyAt('03:15');
```

- **credits:check-overdues** – identifica cuotas vencidas y emite `InstallmentOverdue`.
- **credits:apply-penalties** – suma cargos de mora según política interna.

---

## 7. Laravel Nova (Resources + Dashboard)

| Recurso Nova | Elementos clave |
|--------------|-----------------|
| **CreditAccount** | Cards: *Balance*, *% Pagado*.<br>Lenses: _Overdue_, _In Grace_.<br>Actions: “Registrar pago”, “Conceder gracia”. |
| **CreditInstallment** | Campos: due_date, status (badge), amount_due, interest_portion. |
| **CreditPayment** | Campos: amount, paid_at, method. |
| **Customer** | Panel “Crédito” con saldo actual, próximas cuotas, estado. |

**Dashboard global:**

* Métrica **Cartera total** = `SUM(principal - total_paid)`.
* _Trend_ de abonos últimos 30 días.
* _Partition_ por `status`.

---

## 8. Validaciones y reglas de negocio

- No otorgar crédito si el cliente tiene > *X* días de mora en otro crédito.  
- Pago mayor a cuota → saldo a favor (`AccountEntry` crédito) aplicado a siguiente cuota.  
- Respetar `grace_period_days`: marcar `in_grace` y suspender penalidades.  
- Usa helper `Money` para operaciones monetarias.

---

## 9. Pruebas

- **Feature tests**  
  1. Apertura de crédito genera cuotas exactas.  
  2. Pago parcial → cuota pendiente con resto correcto.  
  3. Simulación de no‑pago → se marca _overdue_ y aplica penalidad.  

Usa `Carbon::setTestNow()` para controlar fechas y `assertDatabaseHas`.

---

## 10. Documentación interna

1. **Diagrama ER** (Mermaid/PlantUML).  
2. **Flowchart** “ciclo de vida del crédito”.  
3. Manual Nova: registrar créditos, aplicar pagos, conceder gracia.

---

## 11. Extras sugeridos

- **Notifications** (email/SMS) al vencer cuota o cambiar estado.  
- **Observer** `CreditPayment` para integrar con contabilidad si existe otro microservicio.  
- **API** `/api/credits` – resumen para apps móviles.  
- **Policy**: solo roles *finance* y *admin* pueden registrar pagos/crear créditos.  
- **Seeder** con tasas por defecto para dev.  
- Considera **multi‑moneda** (`currency_code`) para el futuro.

---

## 12. Próximos pasos para el desarrollador

1. Crear rama `feature/credits` y generar migraciones.  
2. Generar modelos con `--factory` y `--policy`.  
3. Implementar servicios en `app/Services/Credit`.  
4. Crear Resources Nova y dashboard cards.  
5. Configurar comandos programados.  
6. Escribir pruebas y ejecutar `php artisan test`.  
7. Subir *Merge Request* con cobertura ≥ 80 %.

---

> **¡Éxitos codificando!** Este documento es tu hoja de ruta para un módulo de créditos robusto y mantenible.
