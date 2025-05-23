<template>
  <div class="border-t border-gray-200 px-6 py-4">
    <h3 class="text-xl font-bold mb-4">Actions</h3>
    <div class="flex flex-wrap gap-2">
      <button
        @click="rebootOnu"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        :disabled="actionInProgress"
      >
        Reboot
      </button>
      <button
        @click="resetOnu"
        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
        :disabled="actionInProgress"
      >
        Reiniciar Onu
      </button>
      <button
        v-if="onuDetails.status === 'online'"
        @click="disableOnu"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        :disabled="actionInProgress"
      >
        Disable
      </button>
      <button
        v-else
        @click="enableOnu"
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        :disabled="actionInProgress"
      >
        Enable
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    onuDetails: {
      type: Object,
      required: true
    },
    actionInProgress: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    rebootOnu() {
      this.$emit('reboot')
    },
    resetOnu() {
      this.$emit('reset')
    },
    enableOnu() {
      this.$emit('enable')
    },
    disableOnu() {
      this.$emit('disable')
    }
  }
}
</script>
