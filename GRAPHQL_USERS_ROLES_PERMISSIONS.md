# GraphQL API - Usuarios, Roles y Permisos

## Descripción General

Este documento describe los casos de uso para gestionar usuarios, roles y permisos mediante GraphQL utilizando Laravel Permission (Spatie).

---

## Índice

1. [Gestión de Usuarios](#gestión-de-usuarios)
2. [Gestión de Roles](#gestión-de-roles)
3. [Gestión de Permisos](#gestión-de-permisos)
4. [Asignación de Roles a Usuarios](#asignación-de-roles-a-usuarios)
5. [Asignación de Permisos a Roles](#asignación-de-permisos-a-roles)
6. [Asignación de Permisos Directos a Usuarios](#asignación-de-permisos-directos-a-usuarios)

---

## Gestión de Usuarios

### 1.1 Consultar Usuarios

#### Listar todos los usuarios (paginado)
```graphql
query {
  users(first: 10, page: 1) {
    data {
      id
      name
      email
      telephone
      router_id
      created_at
      updated_at
      roles {
        id
        name
      }
      permissions {
        id
        name
      }
      allPermissions {
        id
        name
      }
    }
    paginatorInfo {
      count
      currentPage
      hasMorePages
      total
    }
  }
}
```

#### Buscar usuarios por nombre o email
```graphql
query {
  users(name: "%john%", first: 10) {
    data {
      id
      name
      email
      roles {
        name
      }
    }
  }
}
```

#### Obtener un usuario específico
```graphql
query {
  user(id: 1) {
    id
    name
    email
    telephone
    router_id
    roles {
      id
      name
      permissions {
        id
        name
      }
    }
    permissions {
      id
      name
    }
    allPermissions {
      id
      name
    }
  }
}
```

### 1.2 Crear Usuario

```graphql
mutation {
  createUser(
    name: "Juan Pérez"
    email: "juan.perez@example.com"
    password: "password123"
    telephone: "+57 300 123 4567"
    router_id: 1
  ) {
    id
    name
    email
    telephone
    created_at
  }
}
```

### 1.3 Actualizar Usuario

```graphql
mutation {
  updateUser(
    id: 1
    name: "Juan Carlos Pérez"
    email: "juan.carlos@example.com"
    telephone: "+57 300 999 8888"
  ) {
    id
    name
    email
    updated_at
  }
}
```

### 1.4 Eliminar Usuario

```graphql
mutation {
  deleteUser(id: 1) {
    success
    message
  }
}
```

---

## Gestión de Roles

### 2.1 Consultar Roles

#### Listar todos los roles
```graphql
query {
  roles {
    id
    name
    guard_name
    created_at
    permissions {
      id
      name
    }
    users {
      id
      name
      email
    }
  }
}
```

#### Obtener un rol específico
```graphql
query {
  role(id: 1) {
    id
    name
    guard_name
    permissions {
      id
      name
    }
    users {
      id
      name
    }
  }
}
```

### 2.2 Crear Rol

```graphql
mutation {
  createRole(
    name: "Técnico"
    guard_name: "web"
  ) {
    id
    name
    guard_name
    created_at
  }
}
```

**Ejemplos de roles comunes:**
- super-admin
- admin
- técnico
- soporte
- vendedor
- facturación

### 2.3 Actualizar Rol

```graphql
mutation {
  updateRole(
    id: 1
    name: "Técnico Senior"
  ) {
    id
    name
    updated_at
  }
}
```

### 2.4 Eliminar Rol

```graphql
mutation {
  deleteRole(id: 1) {
    success
    message
  }
}
```

---

## Gestión de Permisos

### 3.1 Consultar Permisos

#### Listar todos los permisos
```graphql
query {
  permissions {
    id
    name
    guard_name
    created_at
    roles {
      id
      name
    }
  }
}
```

#### Obtener un permiso específico
```graphql
query {
  permission(id: 1) {
    id
    name
    guard_name
    roles {
      id
      name
    }
    users {
      id
      name
    }
  }
}
```

### 3.2 Crear Permiso

```graphql
mutation {
  createPermission(
    name: "create_customers"
    guard_name: "web"
  ) {
    id
    name
    guard_name
    created_at
  }
}
```

**Ejemplos de permisos comunes:**
- create_customers
- edit_customers
- delete_customers
- view_customers
- create_services
- edit_services
- delete_services
- view_services
- create_invoices
- edit_invoices
- delete_invoices
- view_invoices
- manage_tickets
- view_reports
- manage_users
- manage_roles
- manage_permissions

### 3.3 Actualizar Permiso

```graphql
mutation {
  updatePermission(
    id: 1
    name: "manage_all_customers"
  ) {
    id
    name
    updated_at
  }
}
```

### 3.4 Eliminar Permiso

```graphql
mutation {
  deletePermission(id: 1) {
    success
    message
  }
}
```

---

## Asignación de Roles a Usuarios

### 4.1 Asignar un Rol a un Usuario

```graphql
mutation {
  assignRoleToUser(
    user_id: 1
    role_id: 2
  ) {
    id
    name
    roles {
      id
      name
    }
  }
}
```

### 4.2 Remover un Rol de un Usuario

```graphql
mutation {
  removeRoleFromUser(
    user_id: 1
    role_id: 2
  ) {
    id
    name
    roles {
      id
      name
    }
  }
}
```

### 4.3 Sincronizar Roles a un Usuario

**Nota:** Esta operación reemplaza todos los roles existentes del usuario con los nuevos roles especificados.

```graphql
mutation {
  syncRolesToUser(
    user_id: 1
    role_ids: [2, 3, 5]
  ) {
    id
    name
    roles {
      id
      name
    }
  }
}
```

---

## Asignación de Permisos a Roles

### 5.1 Asignar un Permiso a un Rol

```graphql
mutation {
  assignPermissionToRole(
    role_id: 2
    permission_id: 5
  ) {
    id
    name
    permissions {
      id
      name
    }
  }
}
```

### 5.2 Remover un Permiso de un Rol

```graphql
mutation {
  removePermissionFromRole(
    role_id: 2
    permission_id: 5
  ) {
    id
    name
    permissions {
      id
      name
    }
  }
}
```

### 5.3 Sincronizar Permisos a un Rol

**Nota:** Esta operación reemplaza todos los permisos existentes del rol con los nuevos permisos especificados.

```graphql
mutation {
  syncPermissionsToRole(
    role_id: 2
    permission_ids: [1, 2, 3, 4, 5, 10, 15]
  ) {
    id
    name
    permissions {
      id
      name
    }
  }
}
```

---

## Asignación de Permisos Directos a Usuarios

**Nota:** Los permisos directos son útiles para dar permisos específicos a un usuario sin crear un rol nuevo.

### 6.1 Asignar un Permiso Directo a un Usuario

```graphql
mutation {
  assignPermissionToUser(
    user_id: 1
    permission_id: 10
  ) {
    id
    name
    permissions {
      id
      name
    }
    allPermissions {
      id
      name
    }
  }
}
```

### 6.2 Remover un Permiso Directo de un Usuario

```graphql
mutation {
  removePermissionFromUser(
    user_id: 1
    permission_id: 10
  ) {
    id
    name
    permissions {
      id
      name
    }
  }
}
```

### 6.3 Sincronizar Permisos Directos a un Usuario

**Nota:** Esta operación reemplaza todos los permisos directos del usuario (no afecta permisos heredados de roles).

```graphql
mutation {
  syncPermissionsToUser(
    user_id: 1
    permission_ids: [1, 5, 10]
  ) {
    id
    name
    permissions {
      id
      name
    }
    allPermissions {
      id
      name
    }
  }
}
```

---

## Casos de Uso Completos

### Caso 1: Crear un Nuevo Usuario Administrador

```graphql
# Paso 1: Crear el usuario
mutation {
  createUser(
    name: "María González"
    email: "maria.gonzalez@ispgo.com"
    password: "SecurePass123!"
    telephone: "+57 310 555 1234"
  ) {
    id
    name
    email
  }
}

# Paso 2: Asignar rol de admin (asumiendo role_id: 2)
mutation {
  assignRoleToUser(
    user_id: 5
    role_id: 2
  ) {
    id
    name
    roles {
      name
    }
  }
}
```

### Caso 2: Crear un Rol de "Técnico de Campo" con Permisos Específicos

```graphql
# Paso 1: Crear el rol
mutation {
  createRole(
    name: "Técnico de Campo"
    guard_name: "web"
  ) {
    id
    name
  }
}

# Paso 2: Asignar permisos al rol (asumiendo role_id: 5)
mutation {
  syncPermissionsToRole(
    role_id: 5
    permission_ids: [1, 2, 4, 8, 11, 15]  # view_customers, view_services, create_tickets, etc.
  ) {
    id
    name
    permissions {
      name
    }
  }
}
```

### Caso 3: Consultar Todos los Permisos de un Usuario (Incluyendo Heredados de Roles)

```graphql
query {
  user(id: 1) {
    id
    name
    email
    roles {
      id
      name
      permissions {
        id
        name
      }
    }
    permissions {
      id
      name
    }
    allPermissions {
      id
      name
    }
  }
}
```

**Explicación:**
- `roles.permissions`: Permisos heredados de los roles
- `permissions`: Permisos asignados directamente al usuario
- `allPermissions`: Todos los permisos del usuario (roles + directos, sin duplicados)

### Caso 4: Actualizar Permisos de un Rol Completo

```graphql
mutation {
  syncPermissionsToRole(
    role_id: 3
    permission_ids: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
  ) {
    id
    name
    permissions {
      id
      name
    }
    users {
      id
      name
      email
    }
  }
}
```

### Caso 5: Reasignar Roles de un Usuario

```graphql
mutation {
  syncRolesToUser(
    user_id: 8
    role_ids: [3, 5]  # Técnico y Soporte
  ) {
    id
    name
    roles {
      id
      name
    }
  }
}
```

---

## Estructura de Respuesta de Errores

Cuando ocurre un error, GraphQL responde con el siguiente formato:

```json
{
  "errors": [
    {
      "message": "Validation failed for the field [createUser].",
      "extensions": {
        "validation": {
          "email": [
            "The email has already been taken."
          ]
        }
      }
    }
  ]
}
```

---

## Notas Importantes

1. **Guard Name:** Por defecto usa "web". Si usas APIs con autenticación diferente, ajusta el `guard_name`.

2. **Diferencia entre Permisos de Rol y Permisos Directos:**
   - **Permisos de Rol:** Son heredados por todos los usuarios con ese rol.
   - **Permisos Directos:** Son específicos para un usuario individual.

3. **Operaciones Sync vs Assign:**
   - **Assign/Remove:** Agregan o quitan sin afectar los demás.
   - **Sync:** Reemplaza completamente la lista existente.

4. **Autenticación:** Todas las mutaciones requieren autenticación. Asegúrate de incluir el token de autorización en los headers:
   ```
   Authorization: Bearer {token}
   ```

5. **El campo `allPermissions`** en User devuelve todos los permisos efectivos del usuario (combinando los de roles y los directos).

---

## Ejemplo de Estructura de Permisos Recomendada

```
Módulo: Customers
- view_customers
- create_customers
- edit_customers
- delete_customers

Módulo: Services
- view_services
- create_services
- edit_services
- delete_services

Módulo: Invoices
- view_invoices
- create_invoices
- edit_invoices
- delete_invoices
- manage_payments

Módulo: Tickets
- view_tickets
- create_tickets
- edit_tickets
- delete_tickets
- assign_tickets

Módulo: Reports
- view_reports
- export_reports

Módulo: Users & Roles
- view_users
- create_users
- edit_users
- delete_users
- manage_roles
- manage_permissions

Módulo: Configuration
- view_config
- edit_config
```

---

## Endpoints GraphQL

- **Endpoint:** `/graphql`
- **Playground:** `/graphql-playground` (si está habilitado en desarrollo)

---

## Soporte

Para más información sobre Laravel Permission (Spatie):
- Documentación: https://spatie.be/docs/laravel-permission/

Para más información sobre Lighthouse GraphQL:
- Documentación: https://lighthouse-php.com/
