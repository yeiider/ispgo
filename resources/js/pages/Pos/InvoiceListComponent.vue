<template>
  <div v-if="!showViewDetails">
    <div v-if="invoices.length > 0" class="overflow-x-auto mt-10 flex items-center">
      <table class="table-auto w-full bg-white shadow-md rounded-lg">
        <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Cliente</th>
          <th class="px-4 py-2">Subtotal</th>
          <th class="px-4 py-2">Impuesto</th>
          <th class="px-4 py-2">Total</th>
          <th class="px-4 py-2">Estado</th>
          <th class="px-4 py-2">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-100">
          <td class="border px-4 py-2">{{ invoice.increment_id }}</td>
          <td class="border px-4 py-2">{{ invoice.full_name }}</td>
          <td class="border px-4 py-2">{{ formatPrice(invoice.subtotal) }}</td>
          <td class="border px-4 py-2">{{ formatPrice(invoice.tax) }}</td>
          <td class="border px-4 py-2">{{ formatPrice(invoice.total) }}</td>
          <td class="border px-4 py-2">
              <span :class="statusClass(invoice.status)">
                {{ invoice.status }}
              </span>
          </td>
          <td class="border px-4 py-2">
            <button @click="viewDetails(invoice)" class=" text-black px-4 py-2 rounded-md hover:text-blue-700">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-5 hover:text-blue-700">
                <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path
                  d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
              </svg>
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p>No hay facturas disponibles.</p>
    </div>
  </div>
  <div class="" v-if="showViewDetails">
    <InvoicePayDetails :invoice="selectInvoice" @closed="setClosedDetails"/>
  </div>
</template>

<script setup>
import {ref, onMounted, inject, provide} from 'vue';
import axios from 'axios';
import InvoicePayDetails from "./InvoicePayDetails.vue";

const formatPrice = inject('formatPrice');

const invoices = ref([]);
const selectInvoice = ref(null);
const showViewDetails = ref(false);

onMounted(() => {
  axios.get('/invoice/find-by-box')
    .then((response) => {
      invoices.value = response.data.invoices;
    })
    .catch((error) => {
      console.error("Error fetching invoices: ", error);
    });
});

const viewDetails = (invoice) => {
  selectInvoice.value = invoice;
  showViewDetails.value = true;
};

const setClosedDetails = () => {
  showViewDetails.value = false;
}

const showList = () => {
  selectInvoice.value = null;
}




// Clase CSS para el estado
const statusClass = (status) => {
  return status === 'paid' ? 'text-green-500' : 'text-red-500';
};
</script>

<style scoped>
/* Estilos adicionales */
</style>
