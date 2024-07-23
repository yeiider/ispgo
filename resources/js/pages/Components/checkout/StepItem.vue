<template>
  <div class="flex items-center cursor-pointer" @click="emitStepChange">
    <div :class="['flex items-center justify-center w-10 h-10 rounded-full', isActive ? 'bg-black text-white' : 'bg-accent text-accent-foreground']">{{ number }}</div>
    <div class="hidden sm:block ml-2">
      <h3 :class="isActive ? 'text-primary font-semibold' : 'text-accent font-semibold'">{{ title }}</h3>
    </div>
  </div>
</template>

<script>
export default {
  props: ['number', 'title', 'isActive'],
  methods: {
    emitStepChange() {
      this.$emit('click');
    }
  }
};
</script>
