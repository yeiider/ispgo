<template>
  <div class=" invoice">
    <div class="invoice-header mb-4 flex justify-between items-center">
    </div>

    <div class="invoice-body grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
      <div>
        <label class="block text-gray-600">Nombre de usuario</label>
        <input
          type="text"
          v-model="editableInvoice.userName"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>
      <div>
        <label class="block text-gray-600">Fecha pago</label>
        <input
          type="datetime-local"
          v-model="editableInvoice.paymentDate"
          class="border p-2 rounded w-full"
        />
      </div>
      <div>
        <label class="block text-gray-600">Fecha Vencimiento</label>
        <input
          type="date"
          v-model="editableInvoice.dueDate"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>
      <div>
        <label class="block text-gray-600">Forma de pago</label>
        <select v-model="editableInvoice.paymentMethod" class="border p-2 rounded w-full">
          <option value="">Selecciona una opción</option>
          <option value="cash">Efectivo</option>
          <option value="credit">Crédito</option>
          <option value="transfer">Transferencia</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-600">Acción al pagar</label>
        <input
          type="text"
          v-model="editableInvoice.actionOnPayment"
          class="border p-2 rounded w-full"
        />
      </div>
      <div>
        <label class="block text-gray-600">Referencia de Pago</label>
        <input
          type="text"
          v-model="editableInvoice.paymentReference"
          class="border p-2 rounded w-full"
        />
      </div>
      <div>
        <label class="block text-gray-600">Imprimir</label>
        <select v-model="editableInvoice.printOption" class="border p-2 rounded w-full">
          <option value="">Nada</option>
          <option value="receipt">Recibo</option>
          <option value="invoice">Factura</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-600">Total a pagar</label>
        <input
          type="number"
          v-model="editableInvoice.totalToPay"
          class="border p-2 rounded w-full text-green-600"
        />
      </div>
    </div>

    <div class="invoice-footer mt-4 flex space-x-4 justify-center">
      <button @click="registerPayment" class="p-2 bg-blue-500 text-white rounded shadow-md hover:bg-blue-600">
        Guardar Pago
      </button>
      <button @click="attachReceipt" class="p-2 bg-green-500 text-white rounded shadow-md hover:bg-green-600">
        Ver/Adjuntar Comprobante de pago
      </button>
      <button @click="cancel" class="p-2 bg-red-500 text-white rounded shadow-md hover:bg-red-600">
        Cancelar
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const editableInvoice = reactive({
  userName: 'Yorelis Baute Zarraga yorelis-baute-zarraga@global-raice', // Valor por defecto de ejemplo
  paymentDate: new Date().toISOString().substring(0, 16), // Fecha y hora actuales
  dueDate: '2024-09-05', // Fecha de vencimiento de ejemplo
  paymentMethod: '',
  actionOnPayment: '',
  paymentReference: '',
  printOption: '',
  totalToPay: 65000.00, // Total a pagar de ejemplo
});

const registerPayment = () => {
  // Lógica para registrar el pago
  console.log('Registrar pago con los siguientes datos:', editableInvoice);
};

const attachReceipt = () => {
  // Lógica para ver o adjuntar el comprobante de pago
  console.log('Adjuntar comprobante de pago');
};

const cancel = () => {
  // Lógica para cancelar la operación
  console.log('Cancelar operación');
};
</script>

<style scoped>
.invoice {
  max-width: 800px;
  margin: auto;
}
</style>
