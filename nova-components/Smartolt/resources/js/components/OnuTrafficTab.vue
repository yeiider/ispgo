<template>
  <div class="py-4">
    <div v-if="loading" class="flex justify-center items-center p-4">
      <Loader class="text-60"/>
    </div>
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
      <p>{{ error }}</p>
    </div>
    <div v-else>
      <div class="mb-4">
        <p><span class="font-bold">Download:</span> {{ onuStatus?.download || 'N/A' }} Mbps</p>
        <p><span class="font-bold">Upload:</span> {{ onuStatus?.upload || 'N/A' }} Mbps</p>
      </div>

      <!-- Graph Type Selection -->
      <div class="mb-4 flex items-center">
        <label for="graph-type" class="mr-2 font-bold">Time Period:</label>
        <select
          id="graph-type"
          v-model="localGraphType"
          class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          @change="onGraphTypeChange"
        >
          <option value="hourly">Hourly</option>
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
          <option value="yearly">Yearly</option>
        </select>

        <div class="ml-4" v-if="localGraphType === 'hourly'">
          <button
            v-if="!autoRefreshEnabled"
            @click="startAutoRefresh"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
          >
            Auto Refresh
          </button>
          <button
            v-else
            @click="stopAutoRefresh"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
          >
            Stop Refresh
          </button>
        </div>
      </div>

      <!-- Traffic Graph -->
      <div class="border border-gray-200 rounded-md overflow-hidden">
        <img
          v-if="trafficGraphUrl"
          :src="trafficGraphUrl"
          alt="Traffic Graph"
          class="max-w-full h-auto"
          style="max-height: 400px; object-fit: contain;"
          :key="trafficGraphKey"
        />
        <div v-else class="h-64 bg-gray-100 flex items-center justify-center">
          <p class="text-gray-500">No traffic graph available</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    onuStatus: {
      type: Object,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: null
    },
    trafficGraphUrl: {
      type: String,
      default: null
    },
    trafficGraphKey: {
      type: Number,
      default: 0
    },
    selectedGraphType: {
      type: String,
      default: 'hourly'
    },
    autoRefreshEnabled: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      localGraphType: this.selectedGraphType
    }
  },
  watch: {
    selectedGraphType(newValue) {
      this.localGraphType = newValue
    }
  },
  methods: {
    onGraphTypeChange() {
      this.$emit('graph-type-change', this.localGraphType)
    },
    startAutoRefresh() {
      this.$emit('start-auto-refresh')
    },
    stopAutoRefresh() {
      this.$emit('stop-auto-refresh')
    }
  }
}
</script>
