<template>
  <div class="relative p-4">
    <input
      type="text"
      v-model="searchQuery"
      @input="handleInput"
      placeholder="Buscar por nombre del cliente..."
      class="w-full p-2 pl-10 pr-10 border rounded-md focus:outline-none"
    />
    <!-- Ícono de búsqueda -->
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="absolute left-6 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500"
      viewBox="0 0 20 20"
      fill="currentColor"
    >
      <path
        fill-rule="evenodd"
        d="M13.293 14.707a8 8 0 111.414-1.414l4.243 4.243a1 1 0 01-1.414 1.414l-4.243-4.243zM8 14a6 6 0 100-12 6 6 0 000 12z"
        clip-rule="evenodd"
      />
    </svg>
    <!-- Botón para limpiar el input -->
    <button
      v-if="searchQuery"
      @click="clearSearch"
      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-12"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M6.293 4.293a1 1 0 011.414 0L10 6.586l2.293-2.293a1 1 0 111.414 1.414L11.414 8l2.293 2.293a1 1 0 01-1.414 1.414L10 9.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 8 6.293 5.707a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from "axios";

const searchQuery = ref('');
const emit = defineEmits(['searchCustomers']); // Define el evento searchCustomers

function clearSearch() {
  searchQuery.value = '';
  emit('searchCustomers', []);
}

const search = async () => {
  try {
    const response = await axios.get('/customer/search', {
      params: { input: searchQuery.value }
    });
    if (response) {
      emit('searchCustomers', response.data); // Emite el evento con los datos
    }
  } catch (error) {
    console.error('Error fetching customers:', error);
  }
}

const handleInput = () => {
  if (searchQuery.value.length > 3) {
    search();
  }
}
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>
