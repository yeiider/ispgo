# SmartOLT REST API - Documentación

## Descripción

API REST para consumir información de ONUs desde SmartOLT. Esta API está diseñada para ser consumida desde aplicaciones frontend externas como Next.js, React, Vue, etc.

**Base URL**: `https://ispgo.raicesc.net/api/smartolt`

**Autenticación**: Bearer Token (OAuth2)

---

## Endpoints Disponibles

### 1. Traffic Graph - Gráfico de Tráfico

Obtiene el gráfico de tráfico de una ONU específica.

```
GET /api/smartolt/onu/{external_id}/traffic-graph/{graph_type?}
```

#### Parámetros

| Parámetro | Ubicación | Tipo | Requerido | Default | Descripción |
|-----------|-----------|------|-----------|---------|-------------|
| `external_id` | Path | String | Sí | - | Identificador externo único de la ONU |
| `graph_type` | Path | String | No | "hourly" | Tipo de gráfico: `hourly`, `daily`, `weekly`, `monthly`, `yearly` |

#### Headers

```
Authorization: Bearer {access_token}
```

#### Respuesta

- **Content-Type**: `image/png`
- **Body**: Imagen PNG binaria
- **Cache-Control**: `public, max-age=300` (5 minutos)

#### Códigos de Estado

- `200` - Éxito, retorna imagen PNG
- `404` - No se encontró el gráfico para la ONU
- `500` - Error interno del servidor

---

### 2. Signal Graph - Gráfico de Señal

Obtiene el gráfico de señal óptica de una ONU.

```
GET /api/smartolt/onu/{external_id}/signal-graph
```

#### Parámetros

| Parámetro | Ubicación | Tipo | Requerido | Descripción |
|-----------|-----------|------|-----------|-------------|
| `external_id` | Path | String | Sí | Identificador externo único de la ONU |

#### Respuesta

- **Content-Type**: `image/png`
- **Body**: Imagen PNG binaria

---

### 3. ONU Details - Detalles de ONU

Obtiene los detalles completos de una ONU.

```
GET /api/smartolt/onu/{external_id}/details
```

#### Respuesta JSON

```json
{
  "unique_external_id": "HWTC48A8F2B2",
  "sn": "HWTC48A8F2B2",
  "olt_name": "OLT-PRINCIPAL",
  "board": "0",
  "port": "1",
  "onu": "5",
  "onu_type_name": "HG8546M",
  "zone_name": "ZONA-A",
  "name": "Cliente Test",
  "status": "online",
  "signal": "-24.5",
  ...
}
```

---

### 4. ONU Status - Estado de ONU

Obtiene el estado completo de una ONU.

```
GET /api/smartolt/onu/{external_id}/status
```

#### Respuesta JSON

```json
{
  "status": true,
  "response_code": "OK",
  "full_status_json": {
    "ONU details": {...},
    "Optical status": {...},
    "ONU LAN Interfaces status": [...],
    ...
  }
}
```

---

## Ejemplos de Uso

### cURL

```bash
# Obtener gráfico de tráfico mensual
curl -X GET "https://ispgo.raicesc.net/api/smartolt/onu/HWTC48A8F2B2/traffic-graph/monthly" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -o traffic_graph.png

# Obtener detalles de ONU
curl -X GET "https://ispgo.raicesc.net/api/smartolt/onu/HWTC48A8F2B2/details" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"
```

### Next.js (TypeScript)

```typescript
// app/components/OnuTrafficGraph.tsx
'use client';

import { useState } from 'react';

interface OnuTrafficGraphProps {
  externalId: string;
  accessToken: string;
}

export default function OnuTrafficGraph({ externalId, accessToken }: OnuTrafficGraphProps) {
  const [graphType, setGraphType] = useState<string>('hourly');
  const [imageError, setImageError] = useState(false);

  // Construir URL con timestamp para evitar cache
  const imageUrl = `https://ispgo.raicesc.net/api/smartolt/onu/${externalId}/traffic-graph/${graphType}?t=${Date.now()}`;

  return (
    <div className="space-y-4">
      <div>
        <label className="mr-2 font-semibold">Graph Type:</label>
        <select
          value={graphType}
          onChange={(e) => {
            setGraphType(e.target.value);
            setImageError(false);
          }}
          className="border rounded px-3 py-2"
        >
          <option value="hourly">Hourly</option>
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="yearly">Yearly</option>
        </select>
      </div>

      {imageError ? (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          Failed to load traffic graph
        </div>
      ) : (
        <img
          src={imageUrl}
          alt={`Traffic Graph - ${graphType}`}
          className="max-w-full h-auto border rounded"
          onError={() => setImageError(true)}
        />
      )}
    </div>
  );
}
```

### React

```jsx
import React, { useState } from 'react';

function OnuTrafficGraph({ externalId, accessToken }) {
  const [graphType, setGraphType] = useState('hourly');

  const imageUrl = `https://ispgo.raicesc.net/api/smartolt/onu/${externalId}/traffic-graph/${graphType}`;

  return (
    <div>
      <select value={graphType} onChange={(e) => setGraphType(e.target.value)}>
        <option value="hourly">Hourly</option>
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
      </select>

      <img
        src={imageUrl}
        alt="Traffic Graph"
        style={{ maxWidth: '100%', height: 'auto' }}
      />
    </div>
  );
}
```

### Vue 3

```vue
<template>
  <div>
    <select v-model="graphType">
      <option value="hourly">Hourly</option>
      <option value="daily">Daily</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
      <option value="yearly">Yearly</option>
    </select>

    <img
      :src="imageUrl"
      alt="Traffic Graph"
      style="max-width: 100%; height: auto"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  externalId: String,
  accessToken: String
});

const graphType = ref('hourly');

const imageUrl = computed(() => {
  return `https://ispgo.raicesc.net/api/smartolt/onu/${props.externalId}/traffic-graph/${graphType.value}`;
});
</script>
```

### Fetch API (JavaScript puro)

```javascript
// Obtener detalles de ONU
async function getOnuDetails(externalId, accessToken) {
  try {
    const response = await fetch(
      `https://ispgo.raicesc.net/api/smartolt/onu/${externalId}/details`,
      {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Accept': 'application/json'
        }
      }
    );

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching ONU details:', error);
    throw error;
  }
}

// Usar
const onuDetails = await getOnuDetails('HWTC48A8F2B2', 'your-access-token');
console.log(onuDetails);
```

---

## Autenticación

Esta API usa **OAuth2 Bearer Token**. Debes incluir el token de acceso en el header `Authorization`:

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

### Obtener Access Token

```bash
curl -X POST "https://ispgo.raicesc.net/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "your-password"
  }'
```

Respuesta:
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "expires_in": 31536000
}
```

---

## Manejo de Errores

### Error 404 - Not Found

```json
{
  "error": "Traffic graph not found",
  "message": "No traffic graph available for this ONU"
}
```

### Error 500 - Internal Server Error

```json
{
  "error": "Internal server error",
  "message": "Error al conectar con SmartOLT: Connection timeout"
}
```

### Error 401 - Unauthorized

```json
{
  "message": "Unauthenticated."
}
```

---

## Cache

Las imágenes tienen un cache de **5 minutos** (`Cache-Control: public, max-age=300`).

Para forzar actualización, agrega un timestamp en la URL:

```javascript
const imageUrl = `/api/smartolt/onu/${externalId}/traffic-graph/${graphType}?t=${Date.now()}`;
```

---

## CORS

Si estás consumiendo la API desde un dominio diferente, asegúrate de que tu frontend esté en la lista de dominios permitidos en la configuración de CORS de Laravel.

---

## Logs

Todas las peticiones se registran en `storage/logs/laravel.log` con el siguiente formato:

```
[2026-02-06 23:23:46] production.INFO: SmartOLT API - Traffic Graph Request
{"external_id":"HWTC48A8F2B2","graph_type":"monthly","ip":"100.64.0.3"}
```

---

## Referencias

- **Controller**: `App\Http\Controllers\Api\SmartOltController`
- **Routes**: `routes/api.php:79-87`
- **API Manager**: `Ispgo\Smartolt\Services\ApiManager`

---

## Soporte

Para reportar problemas o solicitar nuevas funcionalidades, contacta al equipo de desarrollo.
