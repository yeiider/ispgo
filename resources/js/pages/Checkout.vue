<template>
  <div
    class="relative flex flex-col w-full sm:w-1/2 m-auto mt-[8%] items-center p-6 bg-background rounded-lg shadow-lg">
    <Spinner :isVisible="isLoading"/>
    <StepHeader :currentStep="currentStep" @changeStep="changeStep"/>
    <Reference v-if="currentStep === 1" @nextStep="nextStep" @invoiceFound="setInvoice" @loading="setLoading"/>
    <PaymentMethods v-if="currentStep === 2" :methods="paymentMethods" :invoice="invoice" @nextStep="nextStep"
                    @loading="setLoading"/>
    <ResultSection v-if="currentStep === 3" :transaction="payment"/>
    <Summary :invoice="invoice" :showInvoice="showInvoice"/>
  </div>
</template>

<script>
import {ref, onMounted, provide} from 'vue';
import {usePage} from '@inertiajs/inertia-vue3'; // Importa usePage de Inertia
import axios from 'axios';
import StepHeader from './Components/checkout/StepHeader.vue';
import Reference from './Components/checkout/Reference.vue';
import PaymentMethods from './Components/checkout/PaymentMethods.vue';
import ResultSection from './Components/checkout/ResultSection.vue';
import Summary from './Components/checkout/Summary.vue';
import Spinner from './Components/Spinner.vue';

export default {
  components: {
    Summary,
    StepHeader,
    Reference,
    PaymentMethods,
    ResultSection,
    Spinner
  },
  setup() {
    const currentStep = ref(1);
    const paymentMethods = ref([]);
    const invoice = ref({});
    const showInvoice = ref(false);
    const isLoading = ref(false);

    const changeStep = (step) => {
      if (currentStep.value === 3) return;
      currentStep.value = step;
    };

    const nextStep = () => {
      currentStep.value++;
    };

    const setInvoice = (invoiceData) => {
      invoice.value = invoiceData;
      if (invoiceData) {
        showInvoice.value = true;
      }
    };

    const setLoading = (loading) => {
      isLoading.value = loading;
    };

    // Accede a las props desde usePage
    const {props} = usePage();
    const payment = ref(props.value.payment || null);
    const config = props.value.config;

    // Agregar el método formatPrice a la configuración
    const formatPrice = (price) => {
      if (config.currencySymbol) {
        return `${config.currencySymbol}${price}`;
      }else{
        return `${config.currency} ${price}`;

      }
    };

    // Proveer la configuración y la función formatPrice a los componentes hijos
    provide('config', config);
    provide('formatPrice', formatPrice);

    onMounted(async () => {
      setLoading(true);
      if (payment.value) {
        currentStep.value = 3;
      }
      try {
        const response = await axios.get('/payment/configurations');
        paymentMethods.value = response.data;
      } catch (error) {
        console.error('Error fetching payment methods:', error);
      } finally {
        setLoading(false);
      }
    });

    return {
      currentStep,
      changeStep,
      nextStep,
      paymentMethods,
      invoice,
      setInvoice,
      isLoading,
      setLoading,
      showInvoice,
      payment
    };
  }
};
</script>
