### Configuración vía GraphQL (nuevo frontend)

Este documento describe cómo exponer y consumir la configuración de la aplicación a través de GraphQL. La configuración se basa en `config/settings.php` y se persiste en la tabla `core_config_data` con el esquema Magento‑like: cada valor se identifica por `path` y `scope_id`.

#### Terminología
- `path`: Ruta única del campo de configuración con el formato `seccion/grupo/campo` (por ejemplo: `general/billing_cycle/payment_due_date`).
- `scope_id`: Identificador del alcance de la configuración. Por defecto `0` (global). Permite tener valores diferentes por alcance.

#### Tipología de campos
La API deriva los tipos a partir de `config/settings.php`:
- `text-field`, `textarea-field`, `password-field`, `image-field` → `string`
- `boolean-field` → `boolean`
- `select-field` → `select` (con `options`)

Las opciones (`options`) de un `select-field` pueden provenir de clases proveedoras (por ejemplo `App\Settings\Config\Sources\DaysOfMonth`) que exponen `getConfig()` y retornan:
```
[
  { label: string, value: string|number },
  ...
]
```

---

### Esquema GraphQL agregado

Tipos principales:
```
type ConfigSection { key: String!, label: String, path: String, groups: [ConfigGroup!]! }
type ConfigGroup { key: String!, label: String, path: String, fields: [ConfigField!]! }
type ConfigOption { label: String!, value: String! }
type ConfigField {
  section: String!
  group: String!
  key: String!
  label: String
  description: String
  path: String!
  type: String!
  required: Boolean
  default: String
  options: [ConfigOption!]
  value: String
}

input ConfigItemInput { path: String!, value: String }
type ConfigItem { path: String!, value: String, type: String, label: String }
```

Consultas (Queries):
- `configSchema`: Retorna secciones → grupos → campos (sin valores). Útil para construir formularios.
- `configFields(scope_id: Int = 0)`: Lista aplanada de campos con sus valores actuales para un `scope_id`.
- `configValues(paths: [String!]!, scope_id: Int = 0)`: Retorna valores para rutas específicas.
- `configSearch(term: String!, scope_id: Int = 0)`: Busca por `path`, `label` o `name` y devuelve campos con el valor del `scope` indicado.

Mutación (Mutation):
- `upsertConfigValues(scope_id: Int = 0, items: [ConfigItemInput!]!)`: Valida y guarda `path/value` para el `scope` indicado. Devuelve los items normalizados guardados.

---

### Casos de uso

1) Obtener el esquema (para construir formularios)
```
query {
  configSchema {
    key
    label
    groups {
      key
      label
      fields {
        key
        label
        path
        type
        required
        default
        options { label value }
      }
    }
  }
}
```

2) Listar campos con valores para un scope
```
query($scope:Int){
  configFields(scope_id:$scope){
    section
    group
    key
    path
    type
    value
  }
}
```

3) Obtener valores puntuales por `path`
```
query($paths:[String!]!, $scope:Int){
  configValues(paths:$paths, scope_id:$scope){
    path
    value
    type
    label
  }
}
```

Variables de ejemplo:
```
{
  "scope": 0,
  "paths": [
    "general/billing_cycle/payment_due_date",
    "notifications/email_settings/host"
  ]
}
```

4) Buscar campos por término
```
query($term:String!, $scope:Int){
  configSearch(term:$term, scope_id:$scope){
    path
    label
    type
    value
  }
}
```

Variables de ejemplo:
```
{ "term": "billing", "scope": 0 }
```

5) Actualizar valores (crear/actualizar por `path` + `scope_id`)
```
mutation($scope:Int, $items:[ConfigItemInput!]!){
  upsertConfigValues(scope_id:$scope, items:$items){
    path
    value
    type
    label
  }
}
```

Variables de ejemplo:
```
{
  "scope": 0,
  "items": [
    {"path": "general/billing_cycle/payment_due_date", "value": "15"},
    {"path": "invoice/general/enable_partial_payment", "value": "1"},
    {"path": "notifications/email_settings/host", "value": "smtp.mailtrap.io"}
  ]
}
```

Notas de validación:
- `boolean`: acepta `1|0|true|false|yes|no|on|off` (se guarda como `1`/`0`).
- `integer`: debe ser numérico.
- `select`: el `value` debe existir en `options`.

Persistencia:
- Cada item produce un `updateOrCreate` en `core_config_data` con `scope_id`, `path`, `value`.
- Si no existe un valor guardado, se usa `default` definido en la configuración para exponer valores por defecto en queries.

Autenticación y seguridad:
- Las rutas GraphQL usan el guard `auth:api` (ver `config/lighthouse.php`). Asegúrese de enviar el token Bearer correspondiente.
