# Sistema de Filtrado por Router

## 📋 Resumen Ejecutivo

Este proyecto implementa un **sistema de filtrado basado en Routers** para controlar qué datos puede ver cada usuario. El filtrado se basa **únicamente** en si el usuario tiene un `router_id` asignado, **independientemente de su rol**. Los permisos de acciones (crear, editar, eliminar) se manejan por separado mediante el sistema de roles y permisos de Spatie.

---

## 🎯 Concepto Clave

### ¿Qué es un Router en este contexto?
Un **Router** no es solo un dispositivo de red, sino una **unidad organizativa** que agrupa:
- Clientes (`customers`)
- Servicios (`services`)
- Facturas (`invoices`)
- Usuarios (`users`)

### Lógica de Filtrado (Simplificada)

```
┌─────────────────────────────────────────────────┐
│  ¿El usuario tiene router_id asignado?          │
└────────────────┬────────────────────────────────┘
                 │
        ┌────────┴────────┐
        │                 │
       NO                SI
        │                 │
        ▼                 ▼
   Ver TODO          Ver solo datos
   (sin filtro)      de su router_id
```

**Es así de simple:**
- ✅ Si el usuario **NO tiene** `router_id` → Ve **TODOS** los datos
- ✅ Si el usuario **tiene** `router_id` → Ve **SOLO** los datos de ese router

Los **permisos de rol** (qué puede crear/editar/eliminar) se controlan por separado con Spatie Permissions.

---

## 🔑 Implementación Técnica

### 1. Modelo User (`app/Models/User.php`)

**Campo clave:**
```php
protected $fillable = [
    'router_id',  // ← Esta es la clave del filtrado
    // ... otros campos
];
```

**Métodos principales:**

#### `canSeeAllData(): bool`
```php
public function canSeeAllData(): bool
{
    return is_null($this->router_id);
}
```

**Retorna `true` si:**
- El usuario NO tiene `router_id` asignado

**Retorna `false` si:**
- El usuario tiene `router_id` asignado

#### `shouldFilterByRouter(): bool`
```php
public function shouldFilterByRouter(): bool
{
    return !is_null($this->router_id);
}
```

**Retorna `true` si:**
- El usuario tiene `router_id` asignado

**Retorna `false` si:**
- El usuario NO tiene `router_id` asignado

---

### 2. Global Scopes en los Modelos

Los **Global Scopes** son filtros que se aplican **automáticamente** a todas las queries. Están implementados en el método `boot()` de cada modelo.

#### Customer Model (`app/Models/Customers/Customer.php`)

```php
protected static function boot()
{
    parent::boot();

    // Global Scope: Filter by user's router
    static::addGlobalScope('router_filter', function (Builder $builder) {
        $user = Auth::user();
        
        // Si no hay usuario autenticado, no filtrar
        if (!$user) {
            return;
        }

        // Si no tiene router_id asignado, ve todos los datos
        // Los permisos de rol controlan qué acciones puede realizar
        if (!$user->router_id) {
            return;
        }

        // Filtrar por router_id del usuario
        $builder->where('router_id', $user->router_id);
    });
}
```

**Resultado:** 
Los clientes se filtran automáticamente por el `router_id` del usuario logeado (si lo tiene).

#### Service Model (`app/Models/Services/Service.php`)

```php
protected static function boot()
{
    parent::boot();

    static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Si no tiene router_id asignado, ve todos los datos
        // Los permisos de rol controlan qué acciones puede realizar
        if (!$user->router_id) {
            return;
        }

        // Filtrar por router_id a través del cliente O directamente
        $builder->where(function ($query) use ($user) {
            $query->whereHas('customer', function ($q) use ($user) {
                $q->where('router_id', $user->router_id);
            })->orWhere('router_id', $user->router_id);
        });
    });
}
```

**Resultado:** 
Los servicios se filtran por router a través de la relación con `customer` O directamente si tienen `router_id`.

#### Invoice Model (`app/Models/Invoice/Invoice.php`)

```php
protected static function boot()
{
    parent::boot();

    static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
        $user = Auth::user();
        
        if (!$user) {
            return;
        }

        // Si no tiene router_id asignado, ve todos los datos
        // Los permisos de rol controlan qué acciones puede realizar
        if (!$user->router_id) {
            return;
        }

        // Filtrar por router_id directo O a través del cliente
        $builder->where(function ($query) use ($user) {
            $query->where('router_id', $user->router_id)
                ->orWhereHas('customer', function ($q) use ($user) {
                    $q->where('router_id', $user->router_id);
                });
        });
    });
}
```

**Resultado:** 
Las facturas se filtran por router directamente O a través de la relación con `customer`.

---

## 🔄 Diagrama de Flujo

```
┌─────────────────────────────────────────────────────────┐
│              Usuario hace una Query                      │
│         Ejemplo: Customer::all()                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│         Global Scope se ejecuta automáticamente          │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
           ┌─────────────────┐
           │ ¿Usuario auth?  │
           └────┬────────┬───┘
                │ NO     │ SI
                ▼        ▼
            No filtrar   Continuar
                         │
                         ▼
           ┌──────────────────────────┐
           │ ¿Tiene router_id?        │
           └────┬─────────────────┬───┘
                │ NO              │ SI
                ▼                 ▼
            No filtrar     FILTRAR POR router_id
                              │
                              ▼
        ┌─────────────────────────────────────────┐
        │  WHERE router_id = user->router_id      │
        │     O (para Service/Invoice)            │
        │  WHERE customer.router_id = ...         │
        └─────────────────────────────────────────┘
```

**Nota:** Los permisos de rol (crear, editar, eliminar) se evalúan **después** mediante Spatie Permissions, son independientes del filtrado de datos.

---

## 📊 Estructura de Base de Datos

### Tabla: users
```sql
┌─────────────┬──────────────┬─────────────┬─────────────────────────┐
│ id          │ router_id    │ role        │ ¿Qué ve?                │
├─────────────┼──────────────┼─────────────┼─────────────────────────┤
│ 1           │ NULL         │ super-admin │ Todo (sin filtro)       │
│ 2           │ NULL         │ admin       │ Todo (sin filtro)       │
│ 3           │ 1            │ admin       │ Solo router 1           │
│ 4           │ 2            │ user        │ Solo router 2           │
│ 5           │ 1            │ technician  │ Solo router 1           │
└─────────────┴──────────────┴─────────────┴─────────────────────────┘
```

**Clave:** El filtrado depende **solo** del `router_id`, **no** del `role`.

### Tabla: routers
```sql
┌─────────────┬──────────────┬─────────────┐
│ id          │ code         │ name        │
├─────────────┼──────────────┼─────────────┤
│ 1           │ R001         │ Zona Norte  │
│ 2           │ R002         │ Zona Sur    │
│ 3           │ R003         │ Zona Este   │
└─────────────┴──────────────┴─────────────┘
```

### Tabla: customers
```sql
┌─────────────┬──────────────┬──────────────┐
│ id          │ router_id    │ first_name   │
├─────────────┼──────────────┼──────────────┤
│ 1           │ 1            │ Juan         │
│ 2           │ 1            │ María        │
│ 3           │ 2            │ Pedro        │
└─────────────┴──────────────┴──────────────┘
```

### Tabla: services
```sql
┌─────────────┬──────────────┬──────────────┐
│ id          │ router_id    │ customer_id  │
├─────────────┼──────────────┼──────────────┤
│ 1           │ 1            │ 1            │
│ 2           │ 1            │ 2            │
│ 3           │ 2            │ 3            │
└─────────────┴──────────────┴──────────────┘
```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Usuario sin router_id (ve todo)

```php
// Usuario: cualquier rol SIN router_id
$user = Auth::user(); // router_id = NULL, role = cualquiera

// Ve TODOS los clientes
Customer::all(); 
// SELECT * FROM customers

// Ve TODOS los servicios
Service::all();
// SELECT * FROM services

// Ve TODAS las facturas
Invoice::all();
// SELECT * FROM invoices
```

**Nota:** No importa si es `super-admin`, `admin`, `user` o `technician`. Si `router_id` es `NULL`, ve todo.

### Ejemplo 2: Usuario con router_id (ve solo su router)

```php
// Usuario: cualquier rol CON router_id = 1
$user = Auth::user(); // router_id = 1, role = cualquiera

// Ve solo clientes del router 1
Customer::all(); 
// SELECT * FROM customers WHERE router_id = 1

// Ve solo servicios del router 1
Service::all();
// SELECT * FROM services 
// WHERE (EXISTS (SELECT * FROM customers WHERE services.customer_id = customers.id AND router_id = 1) 
//        OR router_id = 1)

// Ve solo facturas del router 1
Invoice::all();
// SELECT * FROM invoices 
// WHERE (router_id = 1 
//        OR EXISTS (SELECT * FROM customers WHERE invoices.customer_id = customers.id AND router_id = 1))
```

**Nota:** No importa si es `admin`, `user` o `technician`. Si tiene `router_id`, solo ve ese router.

---

## 🚫 Desactivar el Filtro (cuando sea necesario)

Si necesitas desactivar temporalmente el filtro global:

```php
// Para un modelo específico
Customer::withoutGlobalScope('router_filter')->get();

// Para todos los scopes
Customer::withoutGlobalScopes()->get();
```

**⚠️ ADVERTENCIA:** Solo usar en casos muy específicos y controlados.

---

## 🔍 Queries SQL Útiles

### 1. Clientes con 2 o más servicios (respetando filtro de router)

```php
// El filtro se aplica automáticamente
Customer::withCount('services')
    ->having('services_count', '>=', 2)
    ->get();
```

**SQL generado (para user con router_id=1):**
```sql
SELECT customers.*, COUNT(services.id) as services_count
FROM customers
LEFT JOIN services ON customers.id = services.customer_id
WHERE customers.router_id = 1  -- ← Filtro automático
GROUP BY customers.id
HAVING COUNT(services.id) >= 2
```

### 2. Clientes SIN servicios (respetando filtro de router)

```php
// El filtro se aplica automáticamente
Customer::whereDoesntHave('services')->get();
```

**SQL generado (para user con router_id=1):**
```sql
SELECT * FROM customers
WHERE router_id = 1  -- ← Filtro automático
AND NOT EXISTS (
    SELECT * FROM services 
    WHERE services.customer_id = customers.id
)
```

---

## 📝 Mantenimiento y Mejores Prácticas

### ✅ DO (Hacer)

1. **Asignar router_id al crear usuarios:**
   ```php
   User::create([
       'name' => 'Admin Zona Norte',
       'router_id' => 1,  // ← Importante
   ]);
   ```

2. **Asignar router_id a clientes:**
   ```php
   Customer::create([
       'first_name' => 'Juan',
       'router_id' => 1,  // ← Importante
   ]);
   ```

3. **Verificar permisos antes de queries masivas:**
   ```php
   if (Auth::user()->canSeeAllData()) {
       // Operación masiva
   }
   ```

### ❌ DON'T (No hacer)

1. **NO usar `withoutGlobalScope()` sin justificación:**
   ```php
   // ❌ MAL
   Customer::withoutGlobalScope('router_filter')->delete();
   ```

2. **NO olvidar asignar router_id:**
   ```php
   // ❌ MAL
   Customer::create([
       'first_name' => 'Juan',
       // router_id faltante
   ]);
   ```

3. **NO asumir que todos los usuarios ven lo mismo:**
   ```php
   // ❌ MAL
   $totalCustomers = Customer::count(); // Varía según usuario
   ```

---

## 🧪 Debugging y Troubleshooting

### Ver el SQL generado:

```php
// Habilitar query log
DB::enableQueryLog();

Customer::all();

// Ver queries
dd(DB::getQueryLog());
```

### Verificar filtro del usuario actual:

```php
$user = Auth::user();

dump([
    'user_id' => $user->id,
    'router_id' => $user->router_id,
    'is_super_admin' => $user->isSuperAdmin(),
    'can_see_all' => $user->canSeeAllData(),
    'should_filter' => $user->shouldFilterByRouter(),
]);
```

---

## 🎓 Casos de Uso Comunes

### Caso 1: Reportes por Router

```php
// Obtener estadísticas de mi router
$stats = [
    'total_customers' => Customer::count(),
    'total_services' => Service::count(),
    'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
];
// Los filtros se aplican automáticamente
```

### Caso 2: Dashboard por Zona

```php
// El dashboard muestra solo datos del router del usuario
$dashboard = [
    'customers' => Customer::active()->count(),
    'services' => Service::where('service_status', 'active')->count(),
    'revenue' => Invoice::where('status', 'paid')->sum('total'),
];
```

### Caso 3: Búsqueda Global (solo para usuarios sin router_id)

```php
// Solo usuarios sin router_id pueden buscar en TODOS los routers
if (Auth::user()->canSeeAllData()) {
    // Buscar en TODOS los routers (el scope ya no aplica filtro)
    $results = Customer::where('email_address', 'like', "%{$search}%")->get();
} else {
    // Buscar solo en su router (el scope aplica el filtro automáticamente)
    $results = Customer::where('email_address', 'like', "%{$search}%")->get();
}

// O simplemente dejar que el scope haga su trabajo:
$results = Customer::where('email_address', 'like', "%{$search}%")->get();
// Se filtrará automáticamente si el usuario tiene router_id
```

---

## 📚 Resumen

| Concepto | Descripción |
|----------|-------------|
| **Router** | Unidad organizativa que agrupa clientes, servicios y facturas |
| **router_id en User** | Define si el usuario ve todos los datos o solo su router |
| **Global Scope** | Filtro automático aplicado a todas las queries basado en router_id |
| **User sin router_id** | Ve todos los datos sin restricción |
| **User con router_id** | Ve solo su router asignado (independiente del rol) |
| **Permisos de rol** | Se manejan por separado con Spatie (crear, editar, eliminar) |
| **Modelos afectados** | Customer, Service, Invoice |

### 🎯 Separación de Responsabilidades

```
┌────────────────────────────────────────┐
│   FILTRADO DE DATOS (router_id)        │
│   ¿QUÉ PUEDE VER?                      │
│   → Global Scopes                      │
│   → Solo depende de router_id          │
└────────────────────────────────────────┘
                    +
┌────────────────────────────────────────┐
│   PERMISOS DE ACCIONES (roles)         │
│   ¿QUÉ PUEDE HACER?                    │
│   → Spatie Permissions                 │
│   → Crear, editar, eliminar, etc.      │
└────────────────────────────────────────┘
```

---

## 🔗 Archivos Relevantes

- `app/Models/User.php` - Métodos `canSeeAllData()` y `shouldFilterByRouter()`
- `app/Models/Customers/Customer.php` - Global Scope líneas 140-161
- `app/Models/Services/Service.php` - Global Scope líneas 113-138
- `app/Models/Invoice/Invoice.php` - Global Scope líneas 336-362
- `app/Models/Router.php` - Modelo base de Router

---

**Última actualización:** 2026-01-26
