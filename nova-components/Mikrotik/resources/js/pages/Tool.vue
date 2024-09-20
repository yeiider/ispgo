<template>
  <div class="container mx-auto p-4">
    <div class="mb-4 flex justify-between items-center">
      <select v-model="bulkAction" class="p-2 border rounded">
        <option value="sync_rb">Sincronizar con RB</option>
      </select>
      <button
        @click="openPoolModal"
        class="px-4 py-2 bg-green-500 text-white rounded"
        :disabled="selectedPlans.length === 0"
      >
        Sincronizar seleccionados con MikroTik
      </button>
    </div>

    <div class="mb-4">
      <input
        v-model="searchQuery"
        class="w-full p-2 border rounded"
        placeholder="Buscar por nombre o descripción"
        @input="filterPlans"
      />
    </div>

    <table class="w-full bg-white border border-gray-300">
      <thead>
      <tr>
        <th class="p-2 border">
          <input
            type="checkbox"
            :checked="selectAll"
            @change="toggleSelectAll"
          />
        </th>
        <th class="p-2 border">Nombre</th>
        <th class="p-2 border">Descripción</th>
        <th class="p-2 border">Velocidad de descarga</th>
        <th class="p-2 border">Velocidad de subida</th>
        <th class="p-2 border">Precio mensual</th>
        <th class="p-2 border">Tipo de conexión</th>
        <th class="p-2 border">Estado</th>
        <th class="p-2 border">Sincronizado</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="plan in paginatedPlans" :key="plan.id">
        <td class="p-2 border">
          <input
            type="checkbox"
            v-model="selectedPlans"
            :value="plan.id"
          />
        </td>
        <td class="p-2 border">{{ plan.name }}</td>
        <td class="p-2 border">{{ truncate(plan.description, 50) }}</td>
        <td class="p-2 border">{{ plan.download_speed }} Mbps</td>
        <td class="p-2 border">{{ plan.upload_speed }} Mbps</td>
        <td class="p-2 border">{{ formatCurrency(plan.monthly_price) }}</td>
        <td class="p-2 border">{{ plan.connection_type }}</td>
        <td class="p-2 border">{{ plan.status }}</td>
        <td class="p-2 border">
          <span v-if="plan.is_synchronized" class="text-green-500">Sí</span>
          <span v-else class="text-red-500">No</span>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Modal para seleccionar el Pool de IP -->
    <transition name="modal">
      <div v-if="isPoolModalOpen" class="modal-overlay">
        <div class="modal-container">
          <div class="modal-content">
            <h2 class="text-xl mb-4">Seleccionar Pool de IP</h2>

            <!-- Filtro para el tipo de pool -->
            <div class="mb-4">
              <label class="block mb-1">Tipo de Pool:</label>
              <select v-model="selectedPoolType" @change="fetchPools" class="w-full p-2 border rounded">
                <option value="normal">Normal</option>
                <option value="dhcp" disabled>DHCP IPV6 (Próximamente)</option>
              </select>
            </div>

            <!-- Loader mientras cargan los pools -->
            <div v-if="isLoading" class="text-center my-4">
              <span class="text-gray-500">Cargando pools...</span>
            </div>

            <!-- Select para elegir el pool de IP -->
            <div v-if="!isLoading" class="mb-4">
              <label class="block mb-1">Selecciona el Pool de IP:</label>
              <select v-model="selectedPool" class="w-full p-2 border rounded" required>
                <option value="" disabled>Seleccionar pool</option>
                <option v-for="pool in pools" :key="pool['.id']" :value="pool.name">
                  {{ pool.name }} ({{ pool.ranges }})
                </option>
              </select>
            </div>

            <div class="flex justify-end">
              <button
                type="button"
                @click="closePoolModal"
                class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center bg-transparent border-transparent h-9 px-3 text-gray-600 dark:text-gray-400 hover:[&:not(:disabled)]:bg-gray-700/5 dark:hover:[&:not(:disabled)]:bg-gray-950"
              >
                Cancelar
              </button>
              <button
                type="submit"
                @click="syncSelectedPPPProfiles"
                class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&:not(:disabled)]:bg-primary-400 hover:[&:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
              >
                Sincronizar
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <div class="mt-4 flex justify-between items-center">
      <div class="flex items-center">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="mx-1 px-3 py-1 border rounded"
          :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
        >
          Anterior
        </button>
        <button
          v-for="page in displayedPages"
          :key="page"
          @click="currentPage = page"
          :class="['mx-1 px-3 py-1 border rounded', currentPage === page ? 'bg-blue-500 text-white' : '']"
        >
          {{ page }}
        </button>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="mx-1 px-3 py-1 border rounded"
          :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
        >
          Siguiente
        </button>
      </div>
      <div class="flex items-center">
        <span class="mr-2">Elementos por página:</span>
        <select v-model="itemsPerPage" class="p-1 border rounded">
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const plans = ref([])
const pools = ref([])
const filteredPlans = ref([])
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = ref(10)
const selectedPlans = ref([])
const selectedPool = ref('') // Pool seleccionado
const selectedPoolType = ref('normal') // Tipo de pool seleccionado
const isPoolModalOpen = ref(false) // Estado del modal
const isLoading = ref(false) // Loader para los pools

// Abrir el modal para seleccionar el pool de IP
const openPoolModal = () => {
  isPoolModalOpen.value = true
  fetchPools() // Cargar los pools cuando se abre el modal
}

// Cerrar el modal
const closePoolModal = () => {
  isPoolModalOpen.value = false
}

// Cargar los pools de IP basado en el tipo seleccionado
const fetchPools = async () => {
  try {
    isLoading.value = true // Activar loader
    const response = await Nova.request().get('/mikrotik/pools', {
      params: { type: selectedPoolType.value } // Enviar el tipo de pool como parámetro
    })
    pools.value = response.data.data
  } catch (error) {
    console.error('Error al cargar los pools de IP:', error)
  } finally {
    isLoading.value = false // Desactivar loader
  }
}

// Obtener los datos de los planes desde la API
const fetchPlans = async () => {
  try {
    const response = await Nova.request().get('/mikrotik/plans')
    plans.value = response.data.data
    filteredPlans.value = [...plans.value]
  } catch (error) {
    console.error('Error al cargar los planes:', error)
  }
}

// Sincronizar solo los planes seleccionados con el pool de IP seleccionado
const syncSelectedPPPProfiles = async () => {
  try {
    const response = await Nova.request().post('/mikrotik/sync-selected-ppp-profiles', {
      plan_ids: selectedPlans.value,
      pool: selectedPool.value // Enviar el pool seleccionado
    })

    // Actualizar el estado de sincronización local
    const syncedPlans = response.data.data
    plans.value.forEach(plan => {
      const syncedPlan = syncedPlans.find(sp => sp.id === plan.id)
      if (syncedPlan) {
        plan.is_synchronized = syncedPlan.is_synchronized
      }
    })

    // Cerrar el modal después de la sincronización
    closePoolModal()

  } catch (error) {
    console.error('Error durante la sincronización:', error)
  }
}

// Filtrar planes por nombre o descripción
const filterPlans = () => {
  filteredPlans.value = plans.value.filter(plan =>
    plan.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    plan.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
  currentPage.value = 1
}

// Al montar el componente, cargamos los planes
onMounted(() => {
  fetchPlans()
})

const paginatedPlans = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredPlans.value.slice(start, end)
})

const totalPages = computed(() =>
  Math.ceil(filteredPlans.value.length / itemsPerPage.value)
)

const displayedPages = computed(() => {
  const range = 2
  const start = Math.max(1, currentPage.value - range)
  const end = Math.min(totalPages.value, currentPage.value + range)
  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})

const selectAll = computed({
  get: () => selectedPlans.value.length === filteredPlans.value.length,
  set: (value) => {
    selectedPlans.value = value ? filteredPlans.value.map(plan => plan.id) : []
  }
})

const toggleSelectAll = () => {
  selectAll.value = !selectAll.value
}

const truncate = (str, length) => {
  return str.length > length ? str.substring(0, length) + '...' : str
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(value)
}
</script>

<style scoped>
/* Estilos para el modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-container {
  background: white;
  padding: 20px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
  position: relative;
}

.modal-content {
  display: flex;
  flex-direction: column;
}

/* Transición para el modal */
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}
.modal-enter, .modal-leave-to {
  opacity: 0;
}
</style>
