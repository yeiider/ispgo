<template>
  <div>
    <div v-for="customer in customers" :key="customer.customer_id" class="border-b p-4">
      <div @click="toggleCustomerDetails(customer.customer_id)" class="cursor-pointer flex justify-between items-center">
        <span>{{ customer.full_name }}</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          viewBox="0 0 20 20"
          fill="currentColor"
          :class="{'rotate-180': isOpen(customer.customer_id)}"
        >
          <path
            fill-rule="evenodd"
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
      <!-- Detalles del cliente -->
      <div v-if="isOpen(customer.customer_id)" class="mt-2 max-h-[40vh] h-auto overflow-y-scroll">
        <div v-for="invoice in customer.invoices" :key="invoice.increment_id" class="p-2 bg-gray-100 mt-2  rounded flex justify-between cursor-pointer">
          <div class="">
            <div>Precio: {{ formatPrice(invoice.total) }}</div>
            <div>Fecha de Pago: {{ new Date(invoice.due_date).toLocaleDateString() }}</div>
            <div>Increment ID: <b>{{ invoice.increment_id }}</b></div>
          </div>
          <button @click="selectInvoice(invoice,customer)" class=" p-2 m-5" title="Processar">
            <span class="inline-block shrink-0 w-6 h-6"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></span>
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import {inject, ref} from 'vue';
const props = defineProps(['customers']);
const emit = defineEmits(['invoiceSelected','customerSelected']);
const formatPrice = inject('formatPrice');

const openCustomerIds = ref([]);

const toggleCustomerDetails = (customerId) => {
  if (openCustomerIds.value.includes(customerId)) {
    openCustomerIds.value = openCustomerIds.value.filter(id => id !== customerId);
  } else {
    openCustomerIds.value.push(customerId);
  }
};

const isOpen = (customerId) => {
  return openCustomerIds.value.includes(customerId);
};

const selectInvoice = (invoice,customer) => {
  emit('invoiceSelected', invoice);
  emit('customerSelected', customer);
};
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>
