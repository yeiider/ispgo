<template>
  <div class="container mx-auto p-4">
    <div class="mb-4 flex justify-between items-center">
      <button
          @click="openModal"
          class="px-4 py-2 bg-green-500 text-white rounded"
      >
        Crear Servidor DHCPv6
      </button>
    </div>

    <table class="w-full bg-white border border-gray-300">
      <thead>
      <tr>
        <th class="p-2 border">Nombre</th>
        <th class="p-2 border">Interface</th>
        <th class="p-2 border">Pool de IP</th>
        <th class="p-2 border">Acciones</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="dhcp in dhcps" :key="dhcp['.id']">
        <td class="p-2 border">{{ dhcp.name }}</td>
        <td class="p-2 border">{{ dhcp.interface }}</td>
        <td class="p-2 border">{{ dhcp['address-pool'] }}</td>
        <td class="p-2 border">
          <button
              @click="deleteDHCP(dhcp['.id'])"
              class="px-2 py-1 bg-red-500 text-white rounded"
          >
            Eliminar
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Modal para agregar nuevo servidor DHCPv6 -->
    <transition name="modal">
      <div v-if="isModalOpen" class="modal-overlay">
        <div class="modal-container">
          <div class="modal-content">
            <h2 class="text-xl mb-4">Agregar Nuevo Servidor DHCPv6</h2>
            <form @submit.prevent="addDHCP">
              <div class="mb-4">
                <label class="block mb-1">Nombre:</label>
                <input
                    v-model="newDHCP.name"
                    type="text"
                    class="w-full p-2 border rounded"
                    required
                />
              </div>
              <div class="mb-4">
                <label class="block mb-1">Interface:</label>
                <input
                    v-model="newDHCP.interface"
                    type="text"
                    class="w-full p-2 border rounded"
                    required
                />
              </div>
              <div class="mb-4">
                <label class="block mb-1">Pool de IP:</label>
                <select v-model="newDHCP.pool" class="w-full p-2 border rounded" required>
                  <option v-for="pool in pools" :value="pool.name">{{ pool.name }}</option>
                </select>
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

const dhcps = ref([])
const pools = ref([])
const isModalOpen = ref(false)
const newDHCP = ref({
  name: '',
  interface: '',
  pool: ''
})

// Función para cargar los pools de IP y DHCPs al montar el componente
const fetchPools = async () => {
  try {
    const response = await Nova.request().get('/mikrotik/ipv6-pools')
    pools.value = response.data.data
  } catch (error) {
    console.error('Error al cargar los pools de IP:', error)
  }
}

const fetchDHCPs = async () => {
  try {
    const response = await Nova.request().get('/mikrotik/dhcp')
    dhcps.value = response.data.data
  } catch (error) {
    console.error('Error al cargar los servidores DHCP:', error)
  }
}

// Función para agregar un nuevo servidor DHCPv6
const addDHCP = async () => {
  try {
    await Nova.request().post('/mikrotik/dhcp', {
      name: newDHCP.value.name,
      interface: newDHCP.value.interface,
      pool: newDHCP.value.pool
    })
    fetchDHCPs()
    closeModal()
  } catch (error) {
    console.error('Error al agregar el servidor DHCPv6:', error)
  }
}

// Función para eliminar un servidor DHCPv6
const deleteDHCP = async (id) => {
  try {
    await Nova.request().delete(`/mikrotik/dhcp/${id}`)
    fetchDHCPs()
  } catch (error) {
    console.error('Error al eliminar el servidor DHCPv6:', error)
  }
}

// Funciones para manejar el modal
const openModal = () => {
  newDHCP.value = { name: '', interface: '', pool: '' } // Reiniciar el formulario
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

// Cargar los pools y DHCPs al montar el componente
onMounted(() => {
  fetchPools()
  fetchDHCPs()
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
