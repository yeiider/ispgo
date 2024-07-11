<template>
  <div>
    <button
      @click="toggle"
      class="bg-blue-500 text-left px-4 py-2 rounded w-full"
      type="button"
    >
      {{ title }}
    </button>
    <div v-show="isOpen" class="mt-4 p-4 border rounded bg-gray-100">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  name: "Collapsible",
  props: {
    title: String,
    isDefaultOpen: Boolean,
  },
  data() {
    return {
      isOpen: this.isDefaultOpen,
    };
  },
  mounted() {
    console.log(this.title)
  },

  methods: {
    toggle() {
      this.isOpen = !this.isOpen;
    },
  },
}
</script>


<style scoped>

</style>
