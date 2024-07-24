<template>
  <div class="max-w-md mx-auto bg-white text-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      Resumen de Compra
    </h2>
    <div class="mb-4">
      <p class="text-gray-600">Producto:</p>
      <p class="text-lg font-medium">Nombre del Producto</p>
    </div>
    <div class="mb-4">
      <p class="text-gray-600">Impuesto:</p>
      <p class="text-lg font-medium">$10.00</p>
    </div>
    <div class="mb-4">
      <p class="text-gray-600">Descuento:</p>
      <p class="text-lg font-medium text-red-500">-$5.00</p>
    </div>
    <div class="border-t border-gray-300 pt-4">
      <p class="text-gray-600">Total a Pagar:</p>
      <p class="text-xl font-bold text-green-600">$105.00</p>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['nextStep'],
  setup(props, { emit }) {
    const authorize = () => {
      // Placeholder for authorization logic
      alert('Authorization successful!');
      emit('nextStep');
    };

    return { authorize };
  }
};
</script>
