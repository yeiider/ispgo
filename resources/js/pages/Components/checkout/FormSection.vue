<template>
  <div class="p-6">
    <h2  class="text-center text-2xl font-bold mb-4">REFERENCIA  DE PAGO</h2>

    <div class="w-full  ">
      <input
        type="text"
        id="reference"
        v-model="reference"
        placeholder="Ej: 1234567890"
        class="w-full p-2 border border-muted rounded-md bg-input text-muted-foreground focus:outline-none focus:ring focus:ring-primary"
        required
      />
      <button type="button" @click="handleSubmit" class="mt-4 w-full bg-primary text-primary-foreground hover:bg-primary/80 py-2 rounded-md">Continuar</button>
    </div>
  </div>

</template>

<script>
import { ref } from 'vue';

export default {
  emits: ['nextStep'],
  setup(props, { emit }) {
    const reference = ref('');

    const handleSubmit = () => {
      alert(`Submitted reference: ${reference.value}`);
      emit('nextStep');
    };

    return {reference, handleSubmit};
  }
};
</script>
