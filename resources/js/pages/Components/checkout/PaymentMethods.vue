<template>
  <div class="container mx-auto p-6">
    <h2 class="text-center text-2xl font-bold hidden sm:block mb-4">PASARELAS DE PAGO</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-4 sm:w-6/12 m-auto mt-5">
      <component
        v-for="method in methods"
        :key="method.payment_code"
        :is="method.payment_component"
        :method="method"
        :invoice="invoice"
      ></component>
    </div>
  </div>
</template>

<script>
import Payu from './PaymentMethds/Payu.vue';
import Wompi from './PaymentMethds/Wompi.vue';

export default {
  emits: ['nextStep'],
  props: ['methods','invoice'],
  components: {
    Payu,
    Wompi
  },
  methods: {
    getComponentName(paymentCode) {
      return paymentCode;
    }
  },
  setup(props, {emit}) {
    const authenticate = () => {
      alert('Authentication successful!');
      emit('nextStep');
    };

    return {authenticate};
  }
};
</script>
