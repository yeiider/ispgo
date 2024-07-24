<template>
  <div class="flex justify-between items-center w-10/12 mb-4">
    <StepItem v-for="(step, index) in steps" :key="index" :number="step.number" :title="step.title" :isActive="currentStep >= step.number" @click="changeStep(step.number)" />
  </div>
</template>

<script>
import StepItem from './StepItem.vue';

export default {
  props: ['currentStep'],
  components: {
    StepItem
  },
  data() {
    return {
      steps: [
        {number: 1, title: 'Referencia'},
        {number: 2, title: 'Metodo de pago'},
        {number: 3, title: 'Resumen'}
      ]
    };
  },
  methods: {
    changeStep(step) {
      if (step>this.currentStep)
        return
      this.$emit('changeStep', step);
    }
  }
};
</script>
