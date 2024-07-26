<template>
  <div class="w-full sm:w-5/12 mt-[3%] bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-center items-center mb-4">
      <template v-if="transaction.status === 'APPROVED'">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <h2 class="text-center font-semibold ml-2 text-green-500">Pago Exitoso</h2>
      </template>
      <template v-else-if="transaction.status === 'DECLINED'">
        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <h2 class="text-center font-semibold ml-2 text-yellow-500">Pago Declinado</h2>
      </template>
      <template v-else-if="transaction.status === 'ERROR'">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <h2 class="text-center font-semibold ml-2 text-red-500">Error en el Pago</h2>
      </template>
      <template v-else-if="transaction.status === 'PENDING'">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
        </svg>
        <h2 class="text-center font-semibold ml-2 text-blue-500">Pago en Camino</h2>
      </template>
    </div>
    <p class="text-center mb-6">
      <template v-if="transaction.status === 'APPROVED'">
        La transacción ha sido completada exitosamente.
      </template>
      <template v-else-if="transaction.status === 'DECLINED'">
        La transacción ha sido declinada. Por favor, intente de nuevo.
      </template>
      <template v-else-if="transaction.status === 'ERROR'">
        Ha ocurrido un error en la transacción. Por favor, intente de nuevo.
      </template>
      <template v-else-if="transaction.status === 'PENDING'">
        Tu pago está en camino. Cuando se confirme, se reflejará en tu factura.
      </template>
    </p>
    <div class="summary-content" v-if="transaction.status !== 'PENDING'">
      <div class="summary-item flex justify-between mb-4">
        <span class="summary-label font-bold">Número de Referencia:</span>
        <span class="summary-value">{{ transaction.reference }}</span>
      </div>
      <div class="summary-item flex justify-between mb-4">
        <span class="summary-label font-bold">Monto Pagado:</span>
        <span class="summary-value">{{ formatAmount(transaction.amount, transaction.currency) }}</span>
      </div>
      <div class="summary-item flex justify-between mb-4">
        <span class="summary-label font-bold">Método de Pago:</span>
        <span class="summary-value">{{ transaction.payment_method_type }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    transaction: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatAmount(amount, currency) {
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency }).format(amount);
    }
  }
};
</script>

<style scoped>
.summary-item {
  display: flex;
  justify-content: space-between;
}

.summary-label {
  font-weight: bold;
}

.summary-value {
  color: #333;
}
</style>
