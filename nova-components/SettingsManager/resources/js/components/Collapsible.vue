<template>
  <div>
    <button
      @click="toggle"
      :class="`bg-blue-500 text-left px-4 py-4 rounded w-full flex justify-between ${isOpen ? 'is-open font-bold': ''}`"
      type="button"
    >
      <span>{{ title }}</span>
      <ArrowDown class="arrow"/>
    </button>
    <Transition>
      <div v-show="isOpen" class="mt-4 p-4 border rounded collapse-content">
        <slot></slot>
      </div>
    </Transition>
  </div>
</template>
<script>
import ArrowDown from './icons/ArrowDown.vue'

export default {
  name: "Collapsible",
  props: {
    title: String,
    isDefaultOpen: Boolean,
  },
  components: {
    ArrowDown
  },
  data() {
    return {
      isOpen: this.isDefaultOpen,
    };
  },

  methods: {
    toggle() {
      this.isOpen = !this.isOpen;
    },
  },
}
</script>

<style scoped>

button, button .arrow {
  transition: all 300ms;
}

button.is-open .arrow {
  transform: rotate(-184deg);
}

.v-enter-active,
.v-leave-active,
.v-enter-from,
.v-leave-to {
  transition: all 300ms ease;
}
</style>
