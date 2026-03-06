# Documentación de Asignación de Productos a Usuarios

Esta funcionalidad permite asignar productos del inventario a usuarios (técnicos, empleados, etc.) y gestionar el ciclo de vida de estas asignaciones (entrega, devolución, estado).

## 1. Configuración de Productos

Para que un producto pueda ser asignado, se recomienda marcar el atributo `assignable_to_service` (o usarlo como lógica de negocio para filtrar en el frontend). Este campo es requerido agregarlo al formulario ya existente para actualizar y crear productos.

### Crear/Actualizar Producto
Se ha añadido el campo `assignable_to_service` a las mutaciones de producto.

```graphql
mutation CreateAssignableProduct {
  createInventoryProduct(input: {
    name: "Router Huawei HG8245"
    sku: "HG8245-001"
    price: 50.0
    cost_price: 35.0
    category_id: 1
    url_key: "router-huawei-hg8245"
    assignable_to_service: true
    unit_of_measure: "units"
  }) {
    id
    name
    assignable_to_service
    unit_of_measure
  }
}
```

## 2. Listar Productos Disponibles para Asignación

Puedes filtrar productos que sean asignables.

```graphql
query GetAssignableProducts {
  inventoryProducts(
    assignable_to_service: true
    status: true # Solo activos
    first: 20
  ) {
    data {
      id
      name
      sku
      total_stock
      assignable_to_service
    }
  }
}
```

## 3. Asignar Producto a Usuario

Utiliza la mutación `assignProductToUser`. Esto crea un registro de `EquipmentAssignment`.

```graphql
mutation AssignRouterToTechnician {
  assignProductToUser(
    user_id: 5
    product_id: 10
    quantity: 1
    assigned_at: "2023-10-27 08:30:00"
    condition: "Nuevo en caja"
    notes: "Entrega para instalación de cliente X"
  ) {
    id
    status # Retorna 'assigned' por defecto
    assigned_at
    product {
      name
    }
    user {
      name
    }
  }
}
```

## 4. Listar Asignaciones

Puedes consultar las asignaciones por usuario, producto o estado.

```graphql
query GetTechnicianAssignments {
  equipmentAssignments(
    user_id: 5
    status: "assigned"
  ) {
    data {
      id
      product {
        name
        sku
      }
      quantity
      assigned_at
      condition_on_assignment
    }
  }
}
```

## 5. Devolución o Actualización de Asignación

Para registrar la devolución o cambiar el estado.


## 6. Asignar Producto a Servicio (Materiales)

Se puede asignar un producto directamente a un servicio. Hay dos modos:
1.  **Asignación Normal**: Registra el material en el servicio (no descuenta del stock del técnico).
2.  **Desde Stock de Usuario**: Descuenta el producto del inventario asignado al técnico (`from_user_stock: true`).

### Ejemplo: Asignación desde Stock del Técnico
Este caso es común cuando un técnico instala un equipo que previamente le fue asignado.

```graphql
mutation InstallRouterOnService {
  assignProductToService(
    service_id: 100
    product_id: 10
    quantity: 1
    from_user_stock: true # Importante: Descuenta del usuario
    user_id: 5 # Opcional si el usuario está autenticado, obligatorio si es admin asignando por otro
    notes: "Instalación de Router HG8245"
  ) {
    id
    service {
      id
    }
    product {
      name
    }
    quantity
    from_user_stock
  }
}
```

### Consultar Materiales de un Servicio

```graphql
query GetServiceMaterials {
  serviceMaterials(service_id: 100) {
    data {
      id
      product {
        name
        unit_of_measure
      }
      quantity
      from_user_stock
      user {
        name
      }
      created_at
    }
  }
}
```
