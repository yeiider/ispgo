<template>
  <div class="invoice">
    <div class="invoice-header mb-4 flex justify-between items-center">
      <h2 class="text-lg font-bold">Detalles de la Factura Pagada</h2>
    </div>

    <div class="invoice-body grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
      <!-- ID de Factura -->
      <div>
        <label class="block text-gray-600">ID de la Factura</label>
        <input
          type="text"
          :value="invoice.increment_id"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Nombre del Cliente -->
      <div>
        <label class="block text-gray-600">Nombre del Cliente</label>
        <input
          type="text"
          :value="invoice.full_name"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Email del Cliente -->
      <div>
        <label class="block text-gray-600">Email del Cliente</label>
        <input
          type="email"
          :value="invoice.email_address"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Fecha de Pago -->
      <div>
        <label class="block text-gray-600">Fecha de Pago</label>
        <input
          type="text"
          :value="formattedUpdatedAt"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Fecha de Vencimiento -->
      <div>
        <label class="block text-gray-600">Fecha de Vencimiento</label>
        <input
          type="text"
          :value="formattedDueDate"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Método de Pago -->
      <div>
        <label class="block text-gray-600">Método de Pago</label>
        <input
          type="text"
          :value="invoice.payment_method"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Subtotal -->
      <div>
        <label class="block text-gray-600">Subtotal</label>
        <input
          type="text"
          :value="subtotal"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Impuesto -->
      <div>
        <label class="block text-gray-600">Impuesto</label>
        <input
          type="text"
          :value="tax"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>

      <!-- Total Pagado -->
      <div>
        <label class="block text-gray-600">Total Pagado</label>
        <input
          type="text"
          :value="amount"
          class="border p-2 rounded w-full text-green-600"
          readonly
        />
      </div>

      <!-- Saldo Pendiente -->
      <div>
        <label class="block text-gray-600">Saldo Pendiente</label>
        <input
          type="text"
          :value="invoice.outstanding_balance"
          class="border p-2 rounded w-full text-red-600"
          readonly
        />
      </div>

      <!-- Notas -->
      <div>
        <label class="block text-gray-600">Notas</label>
        <textarea
          :value="invoice.notes"
          class="border p-2 rounded w-full"
          readonly
        ></textarea>
      </div>
    </div>

    <div class="invoice-footer mt-4 flex space-x-4 justify-center">
      <a :href="url" target="_blank" class="p-2 bg-green-500 text-white rounded shadow-md hover:bg-green-600">
        Ver recibo
      </a>
      <button @click="cancel" class="p-2 bg-red-500 text-white rounded shadow-md hover:bg-red-600">
        Limpiar
      </button>
    </div>
  </div>

</template>

<script setup>
import {inject, ref, watch} from 'vue';

// Props y eventos
const props = defineProps(['invoice']);
const emit = defineEmits(['closed']);
const formatPrice = inject('formatPrice');

// Variables locales para fechas formateadas
const formattedUpdatedAt = ref('');
const formattedDueDate = ref('');
const subtotal = ref('')
const tax = ref('')
const amount = ref('')
const url = ref('')
// Función para formatear las fechas
const formatDates = () => {
  if (props.invoice) {
    formattedUpdatedAt.value = props.invoice.updated_at.substring(0, 10);
    formattedDueDate.value = props.invoice.due_date.substring(0, 10);
    subtotal.value = formatPrice(props.invoice.subtotal)
    tax.value = formatPrice(props.invoice.tax)
    amount.value = formatPrice(props.invoice.amount)
    url.value = `/invoice/receipt?reference=${props.invoice.increment_id}`
  }
};

// Watcher para actualizar cuando cambie la prop invoice
watch(() => props.invoice, formatDates, {immediate: true});

// Funciones de botón
const attachReceipt = () => {
  console.log('Adjuntar comprobante de pago');
};

const cancel = () => {
  emit('closed', []);
};
</script>

<style scoped>
.invoice {
  max-width: 800px;
  margin: auto;
}
</style>
