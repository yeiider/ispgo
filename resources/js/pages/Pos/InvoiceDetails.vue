<template>
  <div class="invoice">
    <div class="invoice-header mb-4 flex justify-between items-center">
      <h2 class="text-lg font-bold">Detalles de la Factura</h2>
    </div>

    <div class="invoice-body grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
      <!-- Formulario de Detalles de la Factura -->
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
          readonly
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
          <option value="cash">Efectivo</option>
          <option value="credit">Crédito</option>
          <option value="transfer">Transferencia</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-600">Referencia de Pago</label>
        <input
          type="text"
          v-model="editableInvoice.paymentReference"
          class="border p-2 rounded w-full"
          readonly
          disabled
        />
      </div>

      <div>
        <label class="block text-gray-600">Subtotal</label>
        <input
          type="number"
          v-model="editableInvoice.subtotal"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>
      <div>
        <label class="block text-gray-600">Impuesto</label>
        <input
          type="number"
          v-model="editableInvoice.tax"
          class="border p-2 rounded w-full"
          readonly
        />
      </div>
      <div>
        <label class="block text-gray-600">Total a pagar</label>
        <input
          type="number"
          v-model="editableInvoice.totalToPay"
          class="border p-2 rounded w-full text-green-600"
          readonly
        />
      </div>
      <div>
        <label class="block text-gray-600">Notas</label>
        <textarea
          v-model="editableInvoice.note"
          class="border p-2 rounded w-full"
        ></textarea>
      </div>
    </div>

    <div class="invoice-footer mt-4 flex space-x-4 justify-center">

      <button @click="confirmAndRegisterPayment" v-if="!isPayment"
              class="p-2 bg-blue-500 text-white rounded shadow-md hover:bg-blue-600">
        Guardar Pago
      </button>
      <button @click="attachReceipt" v-if="isPayment"
              class="p-2 bg-green-500 text-white rounded shadow-md hover:bg-green-600">
        Ver recibo
      </button>
      <button @click="cancel" class="p-2 bg-red-500 text-white rounded shadow-md hover:bg-red-600">
        Limpiar
      </button>
    </div>
  </div>
</template>

<script setup>
import {reactive, onMounted, watch, ref} from 'vue';
import axios from "axios";
import Swal from 'sweetalert2';

const emit = defineEmits(['searchCustomers', 'selectedInvoice']); // Define el evento searchCustomers


const props = defineProps(['invoice', 'customer', 'todayBox']);

const editableInvoice = reactive({
  userName: '',
  paymentDate: '',
  dueDate: '',
  paymentMethod: 'cash',
  actionOnPayment: '',
  paymentReference: '',
  note: '',
  subtotal: 0,
  tax: 0,
  totalToPay: 0,
});

const isPayment = ref(false);
const resetEditableInvoice = () => {
  editableInvoice.userName = '';
  editableInvoice.paymentDate = '';
  editableInvoice.dueDate = '';
  editableInvoice.paymentMethod = 'cash';
  editableInvoice.actionOnPayment = '';
  editableInvoice.paymentReference = '';
  editableInvoice.printOption = '';
  editableInvoice.note = '';
  editableInvoice.subtotal = 0;
  editableInvoice.tax = 0;
  editableInvoice.todaytBox = props.todayBox
  editableInvoice.totalToPay = 0;
};

const updateInvoiceData = () => {
  const invoice = props.invoice;
  const customer = props.customer;
  const todayBox = props.todayBox;

  editableInvoice.userName = customer.full_name || '';
  editableInvoice.paymentDate = new Date().toISOString().substring(0, 16);
  editableInvoice.dueDate = invoice.due_date ? invoice.due_date.substring(0, 10) : '';
  editableInvoice.subtotal = parseFloat(invoice.subtotal || 0).toFixed(2);
  editableInvoice.tax = parseFloat(invoice.tax || 0).toFixed(2);
  editableInvoice.totalToPay = parseFloat(invoice.total || 0).toFixed(2);
  editableInvoice.paymentReference = invoice.increment_id || '';
  editableInvoice.todaytBox = todayBox

};

onMounted(() => {
  updateInvoiceData();
});

watch(() => props.invoice, updateInvoiceData, {deep: true, immediate: true});
watch(() => props.customer, updateInvoiceData, {deep: true, immediate: true});

const confirmAndRegisterPayment = () => {
  Swal.fire({
    title: `¿Quieres proceder con el pago usando ${editableInvoice.paymentMethod}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await axios.post('/invoice/apply-payment',
          editableInvoice
        );
        if (response && response.data) {
          Swal.fire({
            title: 'Pago registrado',
            icon: 'success'
          });
          isPayment.value = true;
          //resetEditableInvoice();
        }
      } catch (error) {
        console.error('Error al registrar el pago:', error);
        Swal.fire('Error', 'No se pudo registrar el pago.', 'error');
      }
    }
  });
};

const attachReceipt = () => {
  console.log('Adjuntar comprobante de pago');
};

const cancel = () => {
  resetEditableInvoice()
  emit('searchCustomers', []);
  emit('selectedInvoice', null);

};
</script>

<style scoped>
.invoice {
  max-width: 800px;
  margin: auto;
}
</style>
