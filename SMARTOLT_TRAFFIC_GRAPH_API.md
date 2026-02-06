# SmartOLT - ONU Traffic Graph API

## Descripción

Esta API permite obtener el gráfico de tráfico de una ONU específica mediante su `external_id`. La API retorna una imagen en formato base64 que puede ser mostrada directamente en el navegador.

## Endpoint GraphQL

```graphql
smartOltOnuTrafficGraph(external_id: String!, graph_type: String = "hourly"): SmartOltOnuTrafficGraph
```

## Parámetros

| Parámetro | Tipo | Requerido | Default | Descripción |
|-----------|------|-----------|---------|-------------|
| `external_id` | String | Sí | - | Identificador externo único de la ONU |
| `graph_type` | String | No | "hourly" | Tipo de gráfico a generar |

### Valores permitidos para `graph_type`

⚠️ **IMPORTANTE**: Usa los valores exactos como están escritos aquí:

- `hourly` - Gráfico por hora (default)
- `daily` - Gráfico diario
- `weekly` - Gráfico semanal
- `monthly` - Gráfico mensual
- `yearly` - Gráfico anual (NO usar "year", debe ser "yearly")

## Tipo de Respuesta

```graphql
type SmartOltOnuTrafficGraph {
    image_base64: String!
    content_type: String!
}
```

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `image_base64` | String | Imagen codificada en base64 |
| `content_type` | String | Tipo MIME de la imagen (típicamente "image/png") |

## Ejemplos de Consulta

### Ejemplo 1: Gráfico por hora (default)

```graphql
query {
  smartOltOnuTrafficGraph(external_id: "ONU-12345") {
    image_base64
    content_type
  }
}
```

### Ejemplo 2: Gráfico diario

```graphql
query {
  smartOltOnuTrafficGraph(
    external_id: "ONU-12345"
    graph_type: "daily"
  ) {
    image_base64
    content_type
  }
}
```

### Ejemplo 3: Gráfico semanal

```graphql
query {
  smartOltOnuTrafficGraph(
    external_id: "ONU-12345"
    graph_type: "weekly"
  ) {
    image_base64
    content_type
  }
}
```

## Uso en Frontend (Vue.js)

### Ejemplo de cómo mostrar la imagen en un componente Vue

```vue
<template>
  <div class="traffic-graph-container">
    <h3>ONU Traffic Graph</h3>

    <!-- Selector de tipo de gráfico -->
    <div class="graph-type-selector">
      <label>Graph Type:</label>
      <select v-model="selectedGraphType" @change="loadTrafficGraph">
        <option value="hourly">Hourly</option>
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
      </select>
    </div>

    <!-- Imagen del gráfico -->
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Loading traffic graph...</p>
    </div>

    <img
      v-else-if="trafficGraphUrl"
      :src="trafficGraphUrl"
      alt="ONU Traffic Graph"
      class="traffic-graph-image"
    />

    <div v-else class="error">
      <p>Failed to load traffic graph</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useQuery } from '@vue/apollo-composable';
import gql from 'graphql-tag';

const props = defineProps({
  externalId: {
    type: String,
    required: true
  }
});

const selectedGraphType = ref('hourly');
const trafficGraphUrl = ref(null);
const loading = ref(true);

// Query GraphQL
const TRAFFIC_GRAPH_QUERY = gql`
  query GetOnuTrafficGraph($externalId: String!, $graphType: String!) {
    smartOltOnuTrafficGraph(external_id: $externalId, graph_type: $graphType) {
      image_base64
      content_type
    }
  }
`;

const loadTrafficGraph = async () => {
  loading.value = true;

  const { result, refetch } = useQuery(TRAFFIC_GRAPH_QUERY, {
    externalId: props.externalId,
    graphType: selectedGraphType.value
  });

  // Refetch si ya existe la query
  if (refetch) {
    await refetch();
  }

  if (result.value?.smartOltOnuTrafficGraph) {
    const { image_base64, content_type } = result.value.smartOltOnuTrafficGraph;
    trafficGraphUrl.value = `data:${content_type};base64,${image_base64}`;
  }

  loading.value = false;
};

onMounted(() => {
  loadTrafficGraph();
});
</script>

<style scoped>
.traffic-graph-container {
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.graph-type-selector {
  margin-bottom: 16px;
}

.graph-type-selector label {
  margin-right: 8px;
  font-weight: 500;
}

.graph-type-selector select {
  padding: 6px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
}

.traffic-graph-image {
  max-width: 100%;
  height: auto;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
}

.loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 32px;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top: 4px solid #3b82f6;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  padding: 16px;
  color: #991b1b;
  background: #fee2e2;
  border-radius: 4px;
  text-align: center;
}
</style>
```

### Alternativa: Usando fetch directo (sin Apollo)

```vue
<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  externalId: {
    type: String,
    required: true
  }
});

const selectedGraphType = ref('hourly');
const trafficGraphUrl = ref(null);
const loading = ref(true);

const loadTrafficGraph = async () => {
  loading.value = true;

  try {
    const response = await fetch('/graphql', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        query: `
          query GetOnuTrafficGraph($externalId: String!, $graphType: String!) {
            smartOltOnuTrafficGraph(external_id: $externalId, graph_type: $graphType) {
              image_base64
              content_type
            }
          }
        `,
        variables: {
          externalId: props.externalId,
          graphType: selectedGraphType.value
        }
      })
    });

    const { data } = await response.json();

    if (data?.smartOltOnuTrafficGraph) {
      const { image_base64, content_type } = data.smartOltOnuTrafficGraph;
      trafficGraphUrl.value = `data:${content_type};base64,${image_base64}`;
    }
  } catch (error) {
    console.error('Error loading traffic graph:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadTrafficGraph();
});
</script>
```

## Respuesta de Ejemplo

```json
{
  "data": {
    "smartOltOnuTrafficGraph": {
      "image_base64": "iVBORw0KGgoAAAANSUhEUgAAA...(base64 data)...==",
      "content_type": "image/png"
    }
  }
}
```

## Notas Importantes

1. **Formato de la imagen**: La imagen se retorna codificada en base64, lo que permite mostrarla directamente usando un data URI:
   ```
   data:{content_type};base64,{image_base64}
   ```

2. **Tamaño de la respuesta**: Ten en cuenta que las imágenes en base64 pueden ser grandes. Considera implementar caché en el frontend.

3. **Manejo de errores**: Si la API no puede generar el gráfico (por ejemplo, si el `external_id` no existe), retornará `null`.

4. **Tipos de gráfico**: Asegúrate de usar uno de los valores válidos para `graph_type`. Valores inválidos pueden causar errores.

## Manejo de Errores

```vue
<script setup>
const loadTrafficGraph = async () => {
  try {
    // ... código de fetch ...

    if (!data?.smartOltOnuTrafficGraph) {
      throw new Error('No se pudo obtener el gráfico de tráfico');
    }

    // ... procesar imagen ...
  } catch (error) {
    console.error('Error:', error);
    // Mostrar mensaje de error al usuario
    Nova.error('Error al cargar el gráfico de tráfico');
  }
};
</script>
```

## Referencias

- **API Original**: `\Ispgo\Smartolt\Services\ApiManager::getOnuTrafficGraphByExternalId`
- **Resolver GraphQL**: `App\GraphQL\Queries\SmartOltQuery@getOnuTrafficGraph`
- **Schema GraphQL**: `graphql/smartolt.graphql:227`
