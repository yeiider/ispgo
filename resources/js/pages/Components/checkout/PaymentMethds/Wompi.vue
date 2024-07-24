<template>
  <div class="flex justify-center">
    <a href="#" class="block border rounded-lg p-4 hover:bg-secondary hover:text-secondary-foreground transition">
      <img :src="method.image" :alt="method.payment_code + ' logo'"/>
    </a>
  </div>
</template>

<script>
export default {
  name: 'Wompi',
  props: {
    method: {
      type: Object,
      required: true
    }
  }
};
</script>
