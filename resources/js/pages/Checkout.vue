<template>
  <div class="flex flex-col w-full sm:w-1/2 m-auto mt-5 items-center p-6 bg-background rounded-lg shadow-lg">
    <StepHeader :currentStep="currentStep" @changeStep="changeStep" />
    <FormSection v-if="currentStep === 1" @nextStep="nextStep" />
    <AuthenticationSection v-if="currentStep === 2" @nextStep="nextStep" />
    <AuthorizationSection v-if="currentStep === 3" @nextStep="nextStep" />
    <ResultSection v-if="currentStep === 4" />
  </div>
</template>

<script>
import { ref } from 'vue';
import StepHeader from './Components/checkout/StepHeader.vue';
import FormSection from './Components/checkout/FormSection.vue';
import AuthenticationSection from './Components/checkout/AuthenticationSection.vue';
import AuthorizationSection from './Components/checkout/AuthorizationSection.vue';
import ResultSection from './Components/checkout/ResultSection.vue';

export default {
  components: {
    StepHeader,
    FormSection,
    AuthenticationSection,
    AuthorizationSection,
    ResultSection
  },
  setup() {
    const currentStep = ref(1);

    const changeStep = (step) => {
      currentStep.value = step;
    };

    const nextStep = () => {
      currentStep.value++;
    };

    return { currentStep, changeStep, nextStep };
  }
};
</script>
