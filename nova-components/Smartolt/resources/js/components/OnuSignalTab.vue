<template>
  <div class="py-4">
    <div v-if="loading" class="flex justify-center items-center p-4">
      <Loader class="text-60"/>
    </div>
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
      <p>{{ error }}</p>
    </div>
    <div v-else>
      <div class="mb-6">
        <h4 class="text-lg font-bold mb-2">Optical Status</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p><span class="font-bold">ONU Rx Power:</span> {{ onuStatus?.rx_power || 'N/A' }} dBm</p>
            <p><span class="font-bold">ONU Tx Power:</span> {{ onuStatus?.tx_power || 'N/A' }} dBm</p>
            <p v-if="onuFullStatus?.optical?.['OLT Rx']"><span class="font-bold">OLT Rx Power:</span> {{ onuFullStatus.optical['OLT Rx'] }}</p>
            <p v-if="onuFullStatus?.optical?.['OLT Tx']"><span class="font-bold">OLT Tx Power:</span> {{ onuFullStatus.optical['OLT Tx'] }}</p>
          </div>
          <div>
            <p v-if="onuFullStatus?.optical?.['1310nm Attenuation']"><span class="font-bold">Upstream Attenuation (1310nm):</span> {{ onuFullStatus.optical['1310nm Attenuation'] }}</p>
            <p v-if="onuFullStatus?.optical?.['1490nm Attenuation']"><span class="font-bold">Downstream Attenuation (1490nm):</span> {{ onuFullStatus.optical['1490nm Attenuation'] }}</p>
          </div>
        </div>
      </div>

      <div v-if="onuFullStatus?.['ONU CATV port']" class="mb-6">
        <h4 class="text-lg font-bold mb-2">CATV Status</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p v-if="onuFullStatus['ONU CATV port']['Admin status']"><span class="font-bold">Admin Status:</span> {{ onuFullStatus['ONU CATV port']['Admin status'] }}</p>
            <p v-if="onuFullStatus['ONU CATV port']['State']"><span class="font-bold">State:</span> {{ onuFullStatus['ONU CATV port']['State'] }}</p>
          </div>
          <div>
            <p v-if="onuFullStatus['ONU CATV port']['1550nm Rx']"><span class="font-bold">1550nm Rx:</span> {{ onuFullStatus['ONU CATV port']['1550nm Rx'] }}</p>
            <p v-if="onuFullStatus['ONU CATV port']['RF output']"><span class="font-bold">RF Output:</span> {{ onuFullStatus['ONU CATV port']['RF output'] }}</p>
          </div>
        </div>
      </div>

      <!-- Signal Graph -->
      <div class="mt-6">
        <h4 class="text-lg font-bold mb-2">Signal Graph</h4>
        <div class="border border-gray-200 rounded-md overflow-hidden">
          <img
            v-if="signalGraphUrl"
            :src="signalGraphUrl"
            alt="Signal Graph"
            class="max-w-full h-auto"
            style="max-height: 400px; object-fit: contain;"
          />
          <div v-else class="h-64 bg-gray-100 flex items-center justify-center">
            <button
              @click="fetchSignalGraph"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Load Signal Graph
            </button>
          </div>
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
    onuFullStatus: {
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
    signalGraphUrl: {
      type: String,
      default: null
    }
  },
  methods: {
    fetchSignalGraph() {
      this.$emit('fetch-signal-graph')
    }
  }
}
</script>
