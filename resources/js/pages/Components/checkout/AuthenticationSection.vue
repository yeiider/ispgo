<template>
  <div class="container mx-auto p-6">
    <h2 class="text-center text-2xl font-bold mb-4">PASARELAS DE PAGO</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-4 sm:w-6/12 m-auto mt-5">
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg p-4 hover:bg-secondary hover:text-secondary-foreground transition">
          <img :src="payuImage" alt="PayU logo" />
        </a>
      </div>
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg p-4 hover:bg-secondary hover:text-secondary-foreground transition">
          <img :src="paypalImage" alt="PayPal logo" />
        </a>
      </div>
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg p-4  hover:text-secondary-foreground transition bg-[#080035]">
          <img :src="epaycoImage" alt="ePayco logo" />
        </a>
      </div>
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg p-4 hover:text-secondary-foreground transition bg-[#080035]">
          <img :src="placeToPayImage" alt="Placetopay logo" />
        </a>
      </div>
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg  hover:bg-secondary hover:text-secondary-foreground transition">
          <img :src="wompiImage" alt="Wompi logo" />
        </a>
      </div>
      <div class="flex justify-center">
        <a href="#" class="block border rounded-lg p-4 hover:bg-secondary hover:text-secondary-foreground transition">
          <img :src="mercadoPagoImage" alt="Mercado Pago logo" />
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['nextStep'],
  data() {
    return {
      payuImage: '/img/payments/payu.svg',
      paypalImage: '/img/payments/PayPal.svg',
      placeToPayImage: '/img/payments/placetopay.svg',
      epaycoImage: '/img/payments/epayco.png',
      wompiImage: '/img/payments/wompi.svg',
      mercadoPagoImage: '/img/payments/mercado-pago.svg',
    };
  },
  setup(props, { emit }) {
    const authenticate = () => {
      // Placeholder for authentication logic
      alert('Authentication successful!');
      emit('nextStep');
    };

    return {authenticate};
  }
};
</script>
