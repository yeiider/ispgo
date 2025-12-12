# Two-Step File Upload Pattern

## Resumen

Este documento describe la implementación del patrón **Two-Step File Upload** en ISPGO para manejar la carga de archivos de manera eficiente, evitando payloads grandes en el envío final de formularios y gestionando archivos temporales de forma automática.

## Problema que Resuelve

1. **Límites de payload**: Los APIs tienen límites en el tamaño del body (Max Body Size). Enviar archivos binarios junto con datos del formulario puede exceder estos límites.
2. **Archivos abandonados**: Si un usuario carga una imagen pero abandona el formulario, el archivo queda huérfano en el almacenamiento.
3. **Experiencia de usuario**: Los usuarios necesitan previsualizar imágenes antes de enviar el formulario final.

## Arquitectura de la Solución

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│   Cliente/UI    │────▶│  API Upload Temp │────▶│   S3 /tmp/      │
│                 │     │  (POST /upload)  │     │   (temporal)    │
└─────────────────┘     └──────────────────┘     └─────────────────┘
        │                                                │
        │  preview_url + temp_path                       │
        ▼                                                │
┌─────────────────┐                                      │
│  Previsualizar  │                                      │
│     imagen      │                                      │
└─────────────────┘                                      │
        │                                                │
        │  Submit formulario (solo temp_path string)     │
        ▼                                                │
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  Crear Recurso  │────▶│  Move to Perm.   │────▶│   S3 /products/ │
│  (GraphQL/API)  │     │  (Copy + Delete) │     │   (permanente)  │
└─────────────────┘     └──────────────────┘     └─────────────────┘
```

## Endpoints Disponibles

### REST API

#### 1. Carga Temporal (POST `/api/upload/temp`)

Carga un archivo a la carpeta temporal de S3.

**Request:**
```bash
curl -X POST /api/upload/temp \
  -H "Authorization: Bearer {token}" \
  -F "file=@imagen.jpg" \
  -F "folder=products"  # opcional
```

**Response:**
```json
{
  "success": true,
  "preview_url": "https://bucket.s3.amazonaws.com/tmp/products/uuid_123.jpg",
  "temp_path": "tmp/products/uuid_123.jpg",
  "original_name": "imagen.jpg",
  "mime_type": "image/jpeg",
  "size": 102400
}
```

#### 2. Confirmar Upload (POST `/api/upload/confirm`)

Mueve un archivo de temporal a permanente.

**Request:**
```json
{
  "temp_path": "tmp/products/uuid_123.jpg",
  "destination_folder": "products"
}
```

**Response:**
```json
{
  "success": true,
  "permanent_path": "products/uuid_123.jpg",
  "url": "https://bucket.s3.amazonaws.com/products/uuid_123.jpg"
}
```

#### 3. Eliminar Temporal (DELETE `/api/upload/temp`)

Elimina un archivo temporal (opcional, S3 Lifecycle lo hace automáticamente).

**Request:**
```json
{
  "temp_path": "tmp/products/uuid_123.jpg"
}
```

### GraphQL API

#### 1. Carga Temporal (Base64)

```graphql
mutation UploadTempFile($input: TempUploadInput!) {
  uploadTempFile(input: $input) {
    success
    message
    preview_url
    temp_path
    original_name
    mime_type
    size
  }
}
```

**Variables:**
```json
{
  "input": {
    "file_base64": "iVBORw0KGgoAAAANSUhEUgAAAAE...",
    "file_name": "imagen.jpg",
    "folder": "products"
  }
}
```

#### 2. Confirmar Upload

```graphql
mutation ConfirmUpload($tempPath: String!, $destFolder: String!) {
  confirmFileUpload(temp_path: $tempPath, destination_folder: $destFolder) {
    success
    message
    permanent_path
    url
  }
}
```

#### 3. Crear Producto con Imagen

```graphql
mutation CreateProductWithImage($input: CreateProductWithImageInput!) {
  createInventoryProductWithImage(input: $input) {
    id
    name
    sku
    image
    price
  }
}
```

**Variables:**
```json
{
  "input": {
    "name": "Router WiFi 6",
    "sku": "RW6-001",
    "price": 150.00,
    "cost_price": 100.00,
    "url_key": "router-wifi-6",
    "category_id": "1",
    "image_temp_path": "tmp/products/uuid_123.jpg"
  }
}
```

## Flujo de Uso Completo

### Ejemplo: Crear Producto con Imagen

```javascript
// 1. Usuario selecciona imagen
const file = document.getElementById('imageInput').files[0];

// 2. Cargar imagen temporal
const formData = new FormData();
formData.append('file', file);
formData.append('folder', 'products');

const uploadResponse = await fetch('/api/upload/temp', {
  method: 'POST',
  headers: { 'Authorization': `Bearer ${token}` },
  body: formData
});

const { preview_url, temp_path } = await uploadResponse.json();

// 3. Mostrar previsualización
document.getElementById('preview').src = preview_url;

// 4. Usuario llena el resto del formulario y hace submit
// IMPORTANTE: Solo enviamos temp_path (string), NO el archivo binario
const productData = {
  name: 'Router WiFi 6',
  sku: 'RW6-001',
  price: 150.00,
  cost_price: 100.00,
  url_key: 'router-wifi-6',
  category_id: '1',
  image_temp_path: temp_path  // ¡Solo el path!
};

// 5. Crear producto (la mutación mueve la imagen automáticamente)
const result = await graphqlClient.mutate({
  mutation: CREATE_PRODUCT_WITH_IMAGE,
  variables: { input: productData }
});
```

### Ejemplo: Usuario Cancela el Formulario

```javascript
// Si el usuario cancela, podemos eliminar el archivo temporal
// (Opcional - S3 Lifecycle lo eliminará automáticamente)
await fetch('/api/upload/temp', {
  method: 'DELETE',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ temp_path: tempPath })
});
```

## Limpieza Automática con S3 Lifecycle

Los archivos en `tmp/` son eliminados automáticamente por AWS S3 Lifecycle Policies. No se necesita un cron job.

### Configuración de S3 Lifecycle Policy

```xml
<LifecycleConfiguration>
  <Rule>
    <ID>CleanupTempUploads</ID>
    <Prefix>tmp/</Prefix>
    <Status>Enabled</Status>
    <Expiration>
      <Days>1</Days>
    </Expiration>
  </Rule>
</LifecycleConfiguration>
```

**Pasos para configurar:**

1. Ir a AWS S3 Console
2. Seleccionar el bucket
3. Ir a "Management" → "Lifecycle rules"
4. Crear regla con prefix `tmp/`
5. Configurar expiración en 1-7 días

## Lógica de "Move" (Copy + Delete)

Laravel Storage Facade no tiene un método nativo `move()` para S3, por lo que implementamos la lógica manualmente:

```php
// 1. Copiar archivo a ubicación permanente
Storage::disk('s3')->copy($tempPath, $permanentPath);

// 2. Establecer visibilidad pública
Storage::disk('s3')->setVisibility($permanentPath, 'public');

// 3. Eliminar archivo temporal
Storage::disk('s3')->delete($tempPath);
```

**¿Por qué Copy + Delete en lugar de Move?**
- S3 no soporta operaciones atómicas de "move"
- Copy + Delete garantiza que el archivo existe en el destino antes de eliminar el origen
- Permite manejar errores en cada paso

## Validaciones

### Validaciones de Upload Temporal

- **Tipo**: Solo imágenes (jpeg, jpg, png, gif, webp, svg)
- **Tamaño**: Máximo 5MB
- **Path**: Debe empezar con `tmp/`

### Validaciones al Confirmar

- El archivo debe existir en S3
- El path debe corresponder a la carpeta temporal
- La carpeta destino no puede contener caracteres especiales

## Casos de Borde

### 1. Archivo temporal no existe

```json
{
  "success": false,
  "message": "El archivo temporal no existe o ya fue procesado."
}
```

**Causas posibles:**
- El archivo expiró (S3 Lifecycle)
- Ya fue confirmado/movido
- Path incorrecto

### 2. Error durante la copia

Si falla la copia a la ubicación permanente, el archivo temporal permanece intacto y se puede reintentar.

### 3. Usuario sube múltiples veces

Cada upload genera un UUID único, por lo que no hay colisiones. Los archivos anteriores serán limpiados por S3 Lifecycle.

## Estructura de Carpetas en S3

```
bucket/
├── tmp/                    # Archivos temporales (auto-cleanup)
│   ├── products/
│   │   └── uuid_123.jpg
│   └── avatars/
│       └── uuid_456.png
├── products/               # Imágenes de productos (permanente)
│   └── uuid_123.jpg
├── avatars/                # Avatares de usuarios (permanente)
│   └── uuid_789.png
└── attachments/            # Adjuntos de tickets (permanente)
    └── uuid_abc.pdf
```

## Archivos Implementados

| Archivo | Descripción |
|---------|-------------|
| `app/Http/Controllers/API/FileUploadController.php` | Controlador REST para upload |
| `app/GraphQL/Mutations/FileUploadMutation.php` | Mutación GraphQL para upload |
| `graphql/schema.graphql` | Tipos y mutaciones de File Upload |
| `routes/api.php` | Rutas REST de upload |

## Seguridad

1. **Autenticación**: Todos los endpoints requieren `auth:api`
2. **Validación de tipo**: Solo se aceptan imágenes
3. **Límite de tamaño**: Máximo 5MB
4. **Path validation**: Solo se pueden mover archivos de `tmp/`
5. **Nombres únicos**: UUID + timestamp evitan ataques de sobreescritura

## Testing

### Test de Upload Temporal

```bash
curl -X POST http://localhost/api/upload/temp \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -F "file=@test-image.jpg"
```

### Test de Creación de Producto con Imagen

```bash
curl -X POST http://localhost/graphql \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "query": "mutation($input: CreateProductWithImageInput!) { createInventoryProductWithImage(input: $input) { id name image } }",
    "variables": {
      "input": {
        "name": "Test Product",
        "sku": "TEST-001",
        "price": 100,
        "cost_price": 50,
        "url_key": "test-product",
        "category_id": "1",
        "image_temp_path": "tmp/uuid_123.jpg"
      }
    }
  }'
```
