<template>
  <div class="w-full sm:w-5/12 mt-[10%]">
    <h2 class="text-center font-semibold mb-4">Solicitando Autorización</h2>
    <button type="button" @click="authorize" class="mt-4 w-full bg-primary text-primary-foreground hover:bg-primary/80 py-2 rounded-md">Autorizar</button>
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
