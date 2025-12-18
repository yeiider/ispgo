# Documentación API GraphQL - Inventario y Stock por Bodega

Esta documentación describe los casos de uso de la API GraphQL para la gestión de inventario con stock distribuido por bodegas.

## Índice

1. [Conceptos](#conceptos)
2. [Queries](#queries)
3. [Mutaciones](#mutaciones)
4. [Casos de Uso](#casos-de-uso)

---

## Conceptos

### Modelo de Datos

El sistema de inventario maneja tres entidades principales:

- **Product (Producto)**: Información del producto (nombre, SKU, precio, etc.)
- **Warehouse (Bodega)**: Ubicaciones físicas donde se almacenan productos
- **ProductStock**: Tabla intermedia que registra la cantidad de cada producto en cada bodega

```
┌─────────────┐       ┌──────────────────┐       ┌─────────────┐
│   Product   │──────▶│   ProductStock   │◀──────│  Warehouse  │
│             │ 1   * │                  │ *   1 │             │
│ - id        │       │ - product_id     │       │ - id        │
│ - name      │       │ - warehouse_id   │       │ - name      │
│ - sku       │       │ - quantity       │       │ - address   │
│ - price     │       │ - min_stock      │       │ - code      │
│ ...         │       │ - max_stock      │       └─────────────┘
└─────────────┘       │ - location       │
                      └──────────────────┘
```

---

## Queries

### 1. Listar Productos

```graphql
query ListarProductos($first: Int, $page: Int, $name: String) {
  inventoryProducts(first: $first, page: $page, name: $name) {
    data {
      id
      name
      sku
      price
      brand
      status
      stocks {
        id
        quantity
        warehouse {
          id
          name
        }
      }
    }
    paginatorInfo {
      total
      currentPage
      lastPage
      hasMorePages
    }
  }
}
```

**Variables:**
```json
{
  "first": 10,
  "page": 1,
  "name": "Cable"
}
```

### 2. Obtener Producto por ID

```graphql
query ObtenerProducto($id: ID!) {
  inventoryProduct(id: $id) {
    id
    name
    sku
    price
    cost_price
    brand
    description
    status
    category {
      id
      name
    }
    warehouse {
      id
      name
    }
    stocks {
      id
      quantity
      min_stock
      max_stock
      location
      warehouse {
        id
        name
        code
      }
    }
  }
}
```

### 3. Listar Bodegas

```graphql
query ListarBodegas($first: Int, $page: Int, $name: String) {
  inventoryWarehouses(first: $first, page: $page, name: $name) {
    data {
      id
      name
      code
      address
      total_stock
      stocks {
        id
        quantity
        product {
          id
          name
          sku
        }
      }
    }
    paginatorInfo {
      total
      currentPage
      lastPage
    }
  }
}
```

### 4. Obtener Bodega por ID

```graphql
query ObtenerBodega($id: ID!) {
  inventoryWarehouse(id: $id) {
    id
    name
    code
    address
    stocks {
      id
      quantity
      min_stock
      max_stock
      location
      product {
        id
        name
        sku
        price
      }
    }
  }
}
```

### 4.1 Listar Categorías

```graphql
query ListarCategorias($first: Int, $page: Int, $name: String) {
  inventoryCategories(first: $first, page: $page, name: $name) {
    data {
      id
      name
      description
      url_key
      products {
        id
        name
        sku
      }
    }
    paginatorInfo {
      total
      currentPage
      lastPage
      hasMorePages
    }
  }
}
```

**Variables:**
```json
{
  "first": 20,
  "page": 1,
  "name": "FTTH"
}
```

### 4.2 Obtener Categoría por ID

```graphql
query ObtenerCategoria($id: ID!) {
  inventoryCategory(id: $id) {
    id
    name
    description
    url_key
    products {
      id
      name
      sku
      price
      stocks {
        quantity
        warehouse {
          name
        }
      }
    }
  }
}
```

### 5. Listar Stock con Filtros

```graphql
query ListarStock($product_id: ID, $warehouse_id: ID, $low_stock: Boolean, $first: Int, $page: Int) {
  productStocks(
    product_id: $product_id
    warehouse_id: $warehouse_id
    low_stock: $low_stock
    first: $first
    page: $page
  ) {
    data {
      id
      quantity
      min_stock
      max_stock
      location
      product {
        id
        name
        sku
      }
      warehouse {
        id
        name
        code
      }
    }
    paginatorInfo {
      total
      currentPage
      lastPage
    }
  }
}
```

**Variables para ver stock bajo:**
```json
{
  "low_stock": true,
  "first": 20,
  "page": 1
}
```

### 6. Stock de un Producto en Todas las Bodegas

```graphql
query StockPorProducto($product_id: ID!) {
  productStockByProduct(product_id: $product_id) {
    id
    quantity
    min_stock
    max_stock
    location
    warehouse {
      id
      name
      code
      address
    }
  }
}
```

### 7. Stock de Todos los Productos en una Bodega

```graphql
query StockPorBodega($warehouse_id: ID!) {
  productStockByWarehouse(warehouse_id: $warehouse_id) {
    id
    quantity
    min_stock
    max_stock
    location
    product {
      id
      name
      sku
      price
    }
  }
}
```

### 8. Stock Total de un Producto

```graphql
query StockTotalProducto($product_id: ID!) {
  productTotalStock(product_id: $product_id) {
    product {
      id
      name
      sku
    }
    total_quantity
    warehouses_count
  }
}
```

---

## Mutaciones

### 1. Crear Producto

```graphql
mutation CrearProducto($input: CreateProductInput!) {
  createInventoryProduct(input: $input) {
    id
    name
    sku
    price
    status
  }
}
```

**Variables:**
```json
{
  "input": {
    "name": "Cable UTP Cat6",
    "sku": "CAB-UTP-CAT6-001",
    "price": 25.50,
    "cost_price": 15.00,
    "url_key": "cable-utp-cat6",
    "warehouse_id": "1",
    "category_id": "1",
    "brand": "Panduit",
    "description": "Cable UTP categoría 6 para instalaciones de red",
    "status": true
  }
}
```

### 2. Actualizar Producto

```graphql
mutation ActualizarProducto($id: ID!, $input: UpdateProductInput!) {
  updateInventoryProduct(id: $id, input: $input) {
    id
    name
    price
    status
  }
}
```

**Variables:**
```json
{
  "id": "1",
  "input": {
    "price": 28.00,
    "special_price": 25.00
  }
}
```

### 3. Eliminar Producto

```graphql
mutation EliminarProducto($id: ID!) {
  deleteInventoryProduct(id: $id) {
    success
    message
  }
}
```

### 4. Crear Bodega

```graphql
mutation CrearBodega($input: CreateWarehouseInput!) {
  createInventoryWarehouse(input: $input) {
    id
    name
    code
    address
  }
}
```

**Variables:**
```json
{
  "input": {
    "name": "Bodega Central",
    "code": "BOD-001",
    "address": "Calle Principal #123"
  }
}
```

### 5. Actualizar Bodega

```graphql
mutation ActualizarBodega($id: ID!, $input: UpdateWarehouseInput!) {
  updateInventoryWarehouse(id: $id, input: $input) {
    id
    name
    code
    address
  }
}
```

### 6. Eliminar Bodega

```graphql
mutation EliminarBodega($id: ID!) {
  deleteInventoryWarehouse(id: $id) {
    success
    message
  }
}
```

### 6.1 Crear Categoría

```graphql
mutation CrearCategoria($input: CreateCategoryInput!) {
  createInventoryCategory(input: $input) {
    id
    name
    description
    url_key
  }
}
```

**Variables:**
```json
{
  "input": {
    "name": "FTTH",
    "description": "Equipos de fibra óptica hasta el hogar",
    "url_key": "ftth"
  }
}
```

### 6.2 Actualizar Categoría

```graphql
mutation ActualizarCategoria($id: ID!, $input: UpdateCategoryInput!) {
  updateInventoryCategory(id: $id, input: $input) {
    id
    name
    description
    url_key
  }
}
```

**Variables:**
```json
{
  "id": "1",
  "input": {
    "description": "Equipos y materiales FTTH"
  }
}
```

### 6.3 Eliminar Categoría

```graphql
mutation EliminarCategoria($id: ID!) {
  deleteInventoryCategory(id: $id) {
    success
    message
  }
}
```

**Nota:** No se puede eliminar una categoría si tiene productos asignados.

### 7. Crear/Actualizar Stock (Upsert)

Esta mutación crea o actualiza el stock de un producto en una bodega específica.

```graphql
mutation UpsertStock($input: UpsertProductStockInput!) {
  upsertProductStock(input: $input) {
    id
    quantity
    min_stock
    max_stock
    location
    product {
      id
      name
    }
    warehouse {
      id
      name
    }
  }
}
```

**Variables:**
```json
{
  "input": {
    "product_id": "1",
    "warehouse_id": "2",
    "quantity": 100,
    "min_stock": 10,
    "max_stock": 500,
    "location": "Pasillo A, Estante 3"
  }
}
```

### 8. Actualizar Cantidad de Stock

```graphql
mutation ActualizarCantidadStock($id: ID!, $quantity: Int!) {
  updateStockQuantity(id: $id, quantity: $quantity) {
    id
    quantity
    product {
      id
      name
    }
    warehouse {
      id
      name
    }
  }
}
```

### 9. Incrementar Stock

```graphql
mutation IncrementarStock($product_id: ID!, $warehouse_id: ID!, $amount: Int!) {
  incrementStock(product_id: $product_id, warehouse_id: $warehouse_id, amount: $amount) {
    id
    quantity
    product {
      id
      name
    }
    warehouse {
      id
      name
    }
  }
}
```

**Variables:**
```json
{
  "product_id": "1",
  "warehouse_id": "2",
  "amount": 50
}
```

### 10. Decrementar Stock

```graphql
mutation DecrementarStock($product_id: ID!, $warehouse_id: ID!, $amount: Int!) {
  decrementStock(product_id: $product_id, warehouse_id: $warehouse_id, amount: $amount) {
    id
    quantity
    product {
      id
      name
    }
    warehouse {
      id
      name
    }
  }
}
```

### 11. Transferir Stock entre Bodegas

```graphql
mutation TransferirStock(
  $product_id: ID!
  $from_warehouse_id: ID!
  $to_warehouse_id: ID!
  $amount: Int!
) {
  transferStock(
    product_id: $product_id
    from_warehouse_id: $from_warehouse_id
    to_warehouse_id: $to_warehouse_id
    amount: $amount
  ) {
    success
    message
    from_stock {
      id
      quantity
      warehouse {
        id
        name
      }
    }
    to_stock {
      id
      quantity
      warehouse {
        id
        name
      }
    }
  }
}
```

**Variables:**
```json
{
  "product_id": "1",
  "from_warehouse_id": "1",
  "to_warehouse_id": "2",
  "amount": 25
}
```

### 12. Eliminar Registro de Stock

```graphql
mutation EliminarStock($id: ID!) {
  deleteProductStock(id: $id) {
    success
    message
  }
}
```

### 13. Asignar Múltiples Bodegas a un Producto

Agrega bodegas con stock a un producto existente (no elimina las asignaciones previas).

```graphql
mutation AsignarBodegas($product_id: ID!, $warehouses: [WarehouseStockInput!]!) {
  assignWarehousesToProduct(product_id: $product_id, warehouses: $warehouses) {
    id
    name
    sku
    stocks {
      id
      quantity
      min_stock
      max_stock
      location
      warehouse {
        id
        name
        code
      }
    }
  }
}
```

**Variables:**
```json
{
  "product_id": "1",
  "warehouses": [
    {
      "warehouse_id": "1",
      "quantity": 50,
      "min_stock": 10,
      "max_stock": 200,
      "location": "Estante A1"
    },
    {
      "warehouse_id": "2",
      "quantity": 30,
      "min_stock": 5,
      "max_stock": 100,
      "location": "Rack B2"
    },
    {
      "warehouse_id": "3",
      "quantity": 20,
      "min_stock": 5,
      "location": "Zona C"
    }
  ]
}
```

### 14. Sincronizar Bodegas de un Producto

Reemplaza TODAS las asignaciones de bodegas existentes por las nuevas proporcionadas.

```graphql
mutation SincronizarBodegas($product_id: ID!, $warehouses: [WarehouseStockInput!]!) {
  syncWarehousesToProduct(product_id: $product_id, warehouses: $warehouses) {
    id
    name
    stocks {
      id
      quantity
      warehouse {
        id
        name
      }
    }
  }
}
```

**Variables:**
```json
{
  "product_id": "1",
  "warehouses": [
    {
      "warehouse_id": "1",
      "quantity": 100,
      "min_stock": 20
    },
    {
      "warehouse_id": "4",
      "quantity": 50
    }
  ]
}
```

### 15. Remover Bodega de un Producto

```graphql
mutation RemoverBodega($product_id: ID!, $warehouse_id: ID!) {
  removeWarehouseFromProduct(product_id: $product_id, warehouse_id: $warehouse_id) {
    success
    message
  }
}
```

**Variables:**
```json
{
  "product_id": "1",
  "warehouse_id": "3"
}
```

---

## Casos de Uso

### Caso 1: Registrar nuevo producto con stock inicial en múltiples bodegas

**Escenario:** Un nuevo producto llega y debe registrarse en el sistema con stock inicial en 3 bodegas diferentes.

**Opción 1: Crear producto con bodegas en una sola mutación (Recomendado)**

```graphql
mutation {
  createInventoryProduct(input: {
    name: "Router WiFi 6"
    sku: "RTR-WIFI6-001"
    price: 150.00
    cost_price: 100.00
    url_key: "router-wifi-6"
    category_id: "2"
    brand: "TP-Link"
    status: true
    warehouses: [
      {
        warehouse_id: "1"
        quantity: 50
        min_stock: 5
        max_stock: 100
        location: "Rack A1"
      },
      {
        warehouse_id: "2"
        quantity: 30
        min_stock: 5
        max_stock: 100
        location: "Estante B2"
      },
      {
        warehouse_id: "3"
        quantity: 20
        min_stock: 5
        max_stock: 100
        location: "Zona C"
      }
    ]
  }) {
    id
    name
    sku
    stocks {
      quantity
      warehouse {
        id
        name
      }
    }
  }
}
```

**Opción 2: Crear producto y luego asignar bodegas (en pasos separados)**

1. Crear el producto:

```graphql
mutation {
  createInventoryProduct(input: {
    name: "Router WiFi 6"
    sku: "RTR-WIFI6-001"
    price: 150.00
    cost_price: 100.00
    url_key: "router-wifi-6"
    category_id: "2"
    brand: "TP-Link"
    status: true
  }) {
    id
    name
    sku
  }
}
```

2. Asignar bodegas con stock:

```graphql
mutation {
  assignWarehousesToProduct(
    product_id: "NUEVO_ID"
    warehouses: [
      { warehouse_id: "1", quantity: 50, min_stock: 5, max_stock: 100, location: "Rack A1" },
      { warehouse_id: "2", quantity: 30, min_stock: 5, max_stock: 100, location: "Estante B2" },
      { warehouse_id: "3", quantity: 20, min_stock: 5, max_stock: 100, location: "Zona C" }
    ]
  ) {
    id
    stocks {
      quantity
      warehouse { name }
    }
  }
}
```

---

### Caso 2: Verificar productos con stock bajo

**Escenario:** Un administrador necesita ver todos los productos que están por debajo del stock mínimo para hacer un pedido de reabastecimiento.

```graphql
query {
  productStocks(low_stock: true, first: 50) {
    data {
      id
      quantity
      min_stock
      location
      product {
        id
        name
        sku
        cost_price
      }
      warehouse {
        id
        name
        code
      }
    }
    paginatorInfo {
      total
    }
  }
}
```

---

### Caso 3: Transferir stock entre bodegas

**Escenario:** La bodega 1 tiene exceso de un producto y la bodega 2 tiene poco. Se requiere transferir 25 unidades.

```graphql
mutation {
  transferStock(
    product_id: "5"
    from_warehouse_id: "1"
    to_warehouse_id: "2"
    amount: 25
  ) {
    success
    message
    from_stock {
      quantity
      warehouse { name }
    }
    to_stock {
      quantity
      warehouse { name }
    }
  }
}
```

---

### Caso 4: Consultar disponibilidad de un producto

**Escenario:** Un vendedor necesita saber en qué bodegas hay disponibilidad de un producto específico.

```graphql
query {
  productStockByProduct(product_id: "10") {
    quantity
    location
    warehouse {
      id
      name
      code
      address
    }
  }
  
  productTotalStock(product_id: "10") {
    total_quantity
    warehouses_count
  }
}
```

---

### Caso 5: Recepción de mercancía

**Escenario:** Llega un envío de 100 unidades de un producto a la bodega central.

```graphql
mutation {
  incrementStock(
    product_id: "8"
    warehouse_id: "1"
    amount: 100
  ) {
    id
    quantity
    product {
      name
      sku
    }
    warehouse {
      name
    }
  }
}
```

---

### Caso 6: Venta/Salida de mercancía

**Escenario:** Se realiza una venta de 5 unidades de un producto desde una bodega específica.

```graphql
mutation {
  decrementStock(
    product_id: "8"
    warehouse_id: "1"
    amount: 5
  ) {
    id
    quantity
    product {
      name
    }
    warehouse {
      name
    }
  }
}
```

---

### Caso 7: Inventario completo de una bodega

**Escenario:** Se necesita un reporte de todo el inventario en una bodega específica.

```graphql
query {
  inventoryWarehouse(id: "1") {
    id
    name
    code
    address
    stocks {
      quantity
      min_stock
      max_stock
      location
      product {
        id
        name
        sku
        price
        cost_price
      }
    }
  }
}
```

---

### Caso 8: Gestión de categorías de productos

**Escenario:** Organizar productos en categorías para facilitar la búsqueda y clasificación.

**Paso 1: Crear categorías**

```graphql
mutation {
  ftth: createInventoryCategory(input: {
    name: "FTTH"
    description: "Equipos de fibra óptica hasta el hogar"
    url_key: "ftth"
  }) { id name }
  
  herramientas: createInventoryCategory(input: {
    name: "Herramientas"
    description: "Herramientas para instalación y mantenimiento"
    url_key: "herramientas"
  }) { id name }
  
  cables: createInventoryCategory(input: {
    name: "Cables y Conectores"
    description: "Cables de red, fibra y conectores"
    url_key: "cables-conectores"
  }) { id name }
}
```

**Paso 2: Crear productos asignándolos a categorías**

```graphql
mutation {
  createInventoryProduct(input: {
    name: "ONU GPON"
    sku: "ONU-GPON-001"
    price: 45.00
    cost_price: 30.00
    url_key: "onu-gpon"
    category_id: "1"  # FTTH
    warehouses: [
      { warehouse_id: "1", quantity: 100, min_stock: 20 }
    ]
  }) {
    id
    name
    category {
      id
      name
    }
    stocks {
      quantity
      warehouse { name }
    }
  }
}
```

**Paso 3: Consultar productos por categoría**

```graphql
query {
  inventoryProducts(category_id: "1", first: 50) {
    data {
      id
      name
      sku
      price
      stocks {
        quantity
        warehouse { name }
      }
    }
    paginatorInfo {
      total
    }
  }
}
```

**Paso 4: Ver todas las categorías con sus productos**

```graphql
query {
  inventoryCategories(first: 100) {
    data {
      id
      name
      description
      products {
        id
        name
        sku
      }
    }
  }
}
```

---

## Notas Importantes

1. **Stock mínimo/máximo**: Los campos `min_stock` y `max_stock` son opcionales y se usan para alertas de stock bajo o exceso de inventario.

2. **Transacciones**: Las operaciones de incremento, decremento y transferencia de stock se ejecutan dentro de transacciones para garantizar consistencia.

3. **Validaciones**:
   - No se puede decrementar más stock del disponible
   - No se puede eliminar un producto con stock > 0
   - No se puede eliminar una bodega con stock > 0

4. **Compatibilidad**: El campo `warehouse_id` en productos se mantiene por compatibilidad con la estructura anterior (bodega principal del producto).

5. **Stock total**: El atributo `total_stock` calcula automáticamente la suma de stock en todas las bodegas.
