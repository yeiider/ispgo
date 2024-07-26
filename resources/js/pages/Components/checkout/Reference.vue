<template>
  <div class="p-6">
    <template v-if="showReferenceForm">
      <h4 class="text-center text-2xl font-bold mb-4">REFERENCIA DE PAGO</h4>
      <p class="text-center mb-5">Puedes buscar por tu DNI o referencia de pago</p>

      <div class="w-full">
        <input
          type="text"
          id="reference"
          v-model="reference"
          placeholder="Ej: 1234567890"
          class="w-full p-2 border border-muted rounded-md bg-input text-muted-foreground focus:outline-none focus:ring focus:ring-primary"
          required
        />
        <button type="button" @click="handleSubmit"
                class="mt-4 w-full bg-primary text-primary-foreground hover:bg-primary/80 py-2 rounded-md">Buscar
        </button>
      </div>
    </template>
    <template v-else>
      <h4 class="text-center text-2xl font-bold mb-4 hidden sm:block">RESUMEN DE PAGO</h4>
      <div class="text-center mb-5">
        <p class="mb-2"><strong>Nombre del Cliente:</strong> {{ invoice.customer_name }}</p>
        <p class="mb-2"><strong>Producto:</strong> {{ invoice.product }}</p>
        <p class="mb-2"><strong>Total a Pagar:</strong> {{ formatPrice(invoice.total) }}</p>
        <p class="mb-2"><strong>Fecha Límite de Pago:</strong> {{ new Date(invoice.due_date).toLocaleDateString() }}</p>
      </div>
      <button type="button" @click="confirmPayment"
              class="mt-4 w-full bg-primary text-primary-foreground hover:bg-primary/80 py-2 rounded-md">Proceder al Pago
      </button>
    </template>
  </div>
</template>

<script>
import {inject, onMounted, ref} from 'vue';
import axios from 'axios';

export default {
  emits: ['nextStep', 'invoiceFound', 'loading'],
  setup(props, { emit }) {
    const reference = ref('');
    const showReferenceForm = ref(true);
    const invoice = ref(null);
    const formatPrice = inject('formatPrice');

    const handleSubmit = async () => {
      emit('loading', true);
      try {
        const response = await axios.get('/invoice/search', {
          params: {
            input: reference.value,
          },
        });

        if (response.status === 200 && response.data) {
          invoice.value = response.data.invoice;
          showReferenceForm.value = false;
          emit('invoiceFound', response.data);
        }
      } catch (error) {
        console.error('Error al buscar la factura:', error);
        // Manejar el error según sea necesario
      } finally {
        emit('loading', false);
      }
    };

    const confirmPayment = () => {
      emit('nextStep');
    };

    return { reference, handleSubmit, showReferenceForm, invoice, confirmPayment,formatPrice };
  },

  mounted() {
    if (this.invoice){
      this.showReferenceForm=true;
    }
  }
};
</script>


