<template>
  <div class="receipt border-2 border-gray-400 rounded-md max-w-xs mx-auto p-4 text-center">
    <!-- Shop Info -->
    <h1 class="text-lg font-bold mb-2">SHOP NAME</h1>
    <p class="text-sm mb-2">shop address</p>
    <p class="text-sm mb-2">shop site</p>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Product Info -->
    <div class="text-left mb-4">
      <p class="font-bold">{{ invoice.full_name }}</p>
      <p>{{ invoice.product_description }}</p> <!-- Se puede ajustar dependiendo de cómo se maneje -->
      <p>{{ formatPrice(invoice.amount) }}</p>
    </div>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Subtotal, TAX, Discount -->
    <div class="text-left mb-4">
      <p>Subtotal: <span class="float-right">{{ formatPrice(invoice.subtotal) }}</span></p>
      <p>TAX: <span class="float-right">{{ formatPrice(invoice.tax) }}</span></p>
      <p>Discount: <span class="float-right">-{{ formatPrice(invoice.discount) }}</span></p>
    </div>

    <!-- Total -->
    <div class="font-bold text-lg mb-4">
      <p>Total: <span class="float-right">{{ formatPrice(invoice.total) }}</span></p>
    </div>

    <!-- Divider -->
    <hr class="my-2 border-gray-300"/>

    <!-- Date and Time -->
    <div class="text-left text-sm mb-4">
      <p>{{ formattedDate }}</p>
      <p>{{ formattedTime }}</p>
    </div>

    <!-- Barcode Placeholder -->
    <div class="mb-4">
      <img src="https://dummyimage.com/200x50/000/fff&text=BARCODE" alt="barcode" class="mx-auto" />
    </div>

    <!-- Footer Message -->
    <p class="text-sm mt-4 font-semibold">Thank you for your purchase!</p>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { format } from 'date-fns';

// Props
const props = defineProps(['invoice']);

// Variables locales para fechas formateadas
const formattedDate = ref('');
const formattedTime = ref('');

// Función para formatear las fechas
const formatDateAndTime = () => {
  if (props.invoice) {
    formattedDate.value = format(new Date(props.invoice.updated_at), 'MM/dd/yyyy');
    formattedTime.value = format(new Date(props.invoice.updated_at), 'h:mm:ss a');
  }
};

// Watcher para actualizar cuando cambie la prop invoice
watch(() => props.invoice, formatDateAndTime, { immediate: true });

// Función para formatear precios
const formatPrice = (price) => {
  return parseFloat(price).toLocaleString('es-CO', {
    style: 'currency',
    currency: 'COP',
  });
};
</script>

<style scoped>
.receipt {
  font-family: 'Arial', sans-serif;
}
</style>
