<template>
  <div class="container mx-auto p-4">
    <div class="mb-4 flex justify-between items-center">
      <button
        @click="openModal"
        class="px-4 py-2 bg-green-500 text-white rounded"
      >
        Crear Pool de IPv6
      </button>
    </div>

    <table class="w-full bg-white border border-gray-300">
      <thead>
      <tr>
        <th class="p-2 border">Nombre</th>
        <th class="p-2 border">Prefijo</th>
        <th class="p-2 border">Longitud del Prefijo</th>
        <th class="p-2 border">Comentario</th>
        <th class="p-2 border">Acciones</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="pool in pools" :key="pool['.id']">
        <td class="p-2 border">{{ pool.name }}</td>
        <td class="p-2 border">{{ pool.prefix }}</td>
        <td class="p-2 border">{{ pool['prefix-length'] }}</td>
        <td class="p-2 border">{{ pool.comment }}</td>
        <td class="p-2 border">
          <button
            @click="deletePool(pool['.id'])"
            class="px-2 py-1 bg-red-500 text-white rounded"
          >
            Eliminar
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Modal para agregar nuevo Pool de IPv6 -->
    <transition name="modal">
      <div v-if="isModalOpen" class="modal-overlay">
        <div class="modal-container">
          <div class="modal-content">
            <h2 class="text-xl mb-4">Agregar Nuevo Pool de IPv6</h2>
            <form @submit.prevent="addPool">
              <div class="mb-4">
                <label class="block mb-1">Nombre:</label>
                <input
                  v-model="newPool.name"
                  type="text"
                  class="w-full p-2 border rounded"
                  required
                />
              </div>
              <div class="mb-4">
                <label class="block mb-1">Prefijo:</label>
                <input
                  v-model="newPool.prefix"
                  type="text"
                  class="w-full p-2 border rounded"
                  placeholder="Ej: 2001:db8::/64"
                  required
                />
              </div>
              <div class="mb-4">
                <label class="block mb-1">Longitud del Prefijo:</label>
                <input
                  v-model="newPool.prefixLength"
                  type="number"
                  class="w-full p-2 border rounded"
                  min="1"
                  max="128"
                  required
                />
              </div>
              <div class="mb-4">
                <label class="block mb-1">Comentario:</label>
                <input
                  v-model="newPool.comment"
                  type="text"
                  class="w-full p-2 border rounded"
                />
              </div>
              <div class="flex justify-end">
                <button
                  type="button"
                  @click="closeModal"
                  class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center bg-transparent border-transparent h-9 px-3 text-gray-600 dark:text-gray-400 hover:[&:not(:disabled)]:bg-gray-700/5 dark:hover:[&:not(:disabled)]:bg-gray-950"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&:not(:disabled)]:bg-primary-400 hover:[&:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
                >
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const pools = ref([])
const isModalOpen = ref(false)
const newPool = ref({
  name: '',
  prefix: '',
  prefixLength: '',
  comment: ''
})

// Función para cargar los pools de IPv6 al montar el componente
const fetchPools = async () => {
  try {
    const response = await Nova.request().get('/mikrotik/ipv6-pools')
    pools.value = response.data.data
  } catch (error) {
    console.error('Error al cargar los pools de IPv6:', error)
  }
}

// Función para agregar un nuevo pool de IPv6
const addPool = async () => {
  try {
    await Nova.request().post('/mikrotik/ipv6-pools', {
      name: newPool.value.name,
      prefix: newPool.value.prefix,
      'prefix-length': newPool.value.prefixLength,
      comment: newPool.value.comment
    })

    // Recargar los pools después de agregar
    fetchPools()
    closeModal()
  } catch (error) {
    console.error('Error al agregar el pool de IPv6:', error)
  }
}

// Función para eliminar un pool de IPv6
const deletePool = async (id) => {
  try {
    await Nova.request().delete(`/mikrotik/ipv6-pools/${id}`)
    fetchPools()
  } catch (error) {
    console.error('Error al eliminar el pool de IPv6:', error)
  }
}

// Funciones para manejar el modal
const openModal = () => {
  newPool.value = { name: '', prefix: '', prefixLength: '', comment: '' } // Reiniciar el formulario
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

// Cargar los pools al montar el componente
onMounted(() => {
  fetchPools()
})
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
