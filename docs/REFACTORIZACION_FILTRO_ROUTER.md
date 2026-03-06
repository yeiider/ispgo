# Refactorización del Sistema de Filtrado por Router

## 🎯 Cambio Realizado

Se simplificó la lógica del sistema de filtrado por router para hacerla más eficiente y mantenible.

---

## ⚡ Antes vs Después

### ❌ ANTES (Lógica Compleja)

```php
// Verificaba múltiples condiciones
if ($user->isSuperAdmin()) {
    return; // No filtrar
}

if (!$user->router_id) {
    return; // No filtrar
}

// Filtrar
$builder->where('router_id', $user->router_id);
```

**Problemas:**
- ❌ Mezclaba conceptos de autorización de datos (router) y permisos de acciones (roles)
- ❌ Código redundante
- ❌ Menos eficiente

### ✅ DESPUÉS (Lógica Simplificada)

```php
// Solo verifica router_id
if (!$user->router_id) {
    return; // No filtrar
}

// Filtrar
$builder->where('router_id', $user->router_id);
```

**Ventajas:**
- ✅ Separación clara de responsabilidades
- ✅ Más simple y mantenible
- ✅ Más eficiente
- ✅ Los roles se manejan por separado con Spatie

---

## 📝 Archivos Modificados

### 1. `app/Models/User.php`

**Método `canSeeAllData()`:**
```diff
- public function canSeeAllData(): bool
- {
-     if ($this->isSuperAdmin()) {
-         return true;
-     }
-     if (!$this->router_id) {
-         return true;
-     }
-     return false;
- }

+ public function canSeeAllData(): bool
+ {
+     return is_null($this->router_id);
+ }
```

**Método `shouldFilterByRouter()`:**
```diff
- public function shouldFilterByRouter(): bool
- {
-     return !$this->isSuperAdmin() && !is_null($this->router_id);
- }

+ public function shouldFilterByRouter(): bool
+ {
+     return !is_null($this->router_id);
+ }
```

### 2. `app/Models/Customers/Customer.php`

**Global Scope:**
```diff
  static::addGlobalScope('router_filter', function (Builder $builder) {
      $user = Auth::user();
      
      if (!$user) {
          return;
      }

-     if ($user->isSuperAdmin() || !$user->router_id) {
-         return;
-     }

+     if (!$user->router_id) {
+         return;
+     }
      
      $builder->where('router_id', $user->router_id);
  });
```

### 3. `app/Models/Services/Service.php`

**Global Scope:**
```diff
  static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
      $user = Auth::user();

      if (!$user) {
          return;
      }

-     if ($user->isSuperAdmin() || !$user->router_id) {
-         return;
-     }

+     if (!$user->router_id) {
+         return;
+     }

      $builder->where(function ($query) use ($user) {
          $query->whereHas('customer', function ($q) use ($user) {
              $q->where('router_id', $user->router_id);
          })->orWhere('router_id', $user->router_id);
      });
  });
```

### 4. `app/Models/Invoice/Invoice.php`

**Global Scope:**
```diff
  static::addGlobalScope('router_filter', function (\Illuminate\Database\Eloquent\Builder $builder) {
      $user = Auth::user();
      
      if (!$user) {
          return;
      }

-     if ($user->isSuperAdmin() || !$user->router_id) {
-         return;
-     }

+     if (!$user->router_id) {
+         return;
+     }

      $builder->where(function ($query) use ($user) {
          $query->where('router_id', $user->router_id)
              ->orWhereHas('customer', function ($q) use ($user) {
                  $q->where('router_id', $user->router_id);
              });
      });
  });
```

---

## 🧪 Ejemplos de Comportamiento

### Usuario sin router_id

```php
// Usuario: cualquier rol (super-admin, admin, user, technician)
$user->router_id = null;

// RESULTADO: Ve TODOS los datos
Customer::all(); // No se aplica filtro
Service::all();  // No se aplica filtro
Invoice::all();  // No se aplica filtro
```

### Usuario con router_id

```php
// Usuario: cualquier rol (admin, user, technician)
$user->router_id = 1;

// RESULTADO: Ve SOLO datos del router 1
Customer::all(); // WHERE router_id = 1
Service::all();  // WHERE router_id = 1 (a través de customer o directo)
Invoice::all();  // WHERE router_id = 1 (a través de customer o directo)
```

---

## 🎯 Separación de Responsabilidades

### ¿QUÉ PUEDE VER? (Filtrado de Datos)
- Controlado por: **Global Scopes**
- Depende de: **router_id del usuario**
- Resultado: Filtra queries automáticamente

### ¿QUÉ PUEDE HACER? (Permisos de Acciones)
- Controlado por: **Spatie Permissions**
- Depende de: **Roles y permisos del usuario**
- Resultado: Autoriza acciones (crear, editar, eliminar)

---

## 📊 Diagrama Simplificado

```
ANTES:
Usuario → ¿Super-admin? → ¿router_id? → Filtrar
          ↓                ↓
          No filtrar       No filtrar

DESPUÉS:
Usuario → ¿router_id? → Filtrar
          ↓
          No filtrar
```

---

## ✅ Verificación

Para verificar que todo funciona correctamente:

```php
// 1. Usuario sin router_id
$user = User::find(1); // router_id = NULL
Auth::login($user);
dump(Customer::count()); // Debe mostrar TODOS los clientes

// 2. Usuario con router_id
$user = User::find(3); // router_id = 1
Auth::login($user);
dump(Customer::count()); // Debe mostrar solo clientes del router 1
```

---

## 📚 Documentación Actualizada

Se actualizó el archivo `.agent/FILTRO_POR_ROUTER.md` con:
- ✅ Nueva lógica simplificada
- ✅ Diagramas actualizados
- ✅ Ejemplos revisados
- ✅ Separación de responsabilidades clarificada

---

**Fecha de refactorización:** 2026-01-26
**Motivo:** Simplificación y separación de responsabilidades
