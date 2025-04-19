<script>
export default {
  name: 'Dropdown',
  props: {
    options: {
      type: Array,
      required: true
    },
    placeholder: {
      type: String,
      default: 'Selecciona una opción'
    },
    selected: {
      type: String | Number,
      default: null
    }
  },
  data() {
    return {
      isOpen: false,
      selectedOption: null
    }
  },

  watch: {
    options: {
      handler(newOptions) {
        if (this.selected) {
          const foundOption = newOptions.find(option => option.code === parseInt(this.selected));
          this.selectedOption = foundOption ? foundOption.label : this.selected;
        }
      },
      immediate: true
    }
  },

  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen
    },
    selectOption(option) {
      this.selectedOption = option.label
      this.isOpen = false
      this.$emit('option-selected', option)
      console.log(this.options)
    },
    closeDropdown(e) {
      if (!this.$el.contains(e.target)) {
        this.isOpen = false
      }
    }
  },
  mounted() {
    document.addEventListener('click', this.closeDropdown)
  },

  created() {
    console.log(this.options)
  },

  beforeDestroy() {
    document.removeEventListener('click', this.closeDropdown)
  }
}
</script>

<template>
  <div class="relative">
    <!-- Botón del dropdown -->
    <button
      @click="toggleDropdown"
      class="min-w-48 flex items-center justify-between w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <span>{{ selectedOption || placeholder }}</span>
      <svg
        class="w-5 h-5 ml-2 -mr-1 text-gray-400"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <!-- Lista de opciones -->
    <div
      v-if="isOpen"
      class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
    >
      <ul class="py-1 overflow-auto text-base rounded-md max-h-60 focus:outline-none">
        <li
          v-for="(option, index) in options"
          :key="index"
          @click="selectOption(option)"
          class="relative px-4 py-2 cursor-pointer hover:bg-blue-50 hover:text-blue-700"
          :class="{ 'bg-blue-50 text-blue-700': option.code === parseInt(selected) }"
        >
          {{ option.label }}
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
  .min-w-48 {
    min-width: 12rem;
  }
</style>
