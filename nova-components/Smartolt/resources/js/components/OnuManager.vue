<template>
  <div>
    <Card class="mb-6">
      <div v-if="loading" class="flex justify-center items-center p-4">
        <Loader class="text-60"/>
      </div>
      <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
        <p>{{ error }}</p>
      </div>
      <div v-else-if="!onuDetails" class="p-4">
        <p class="text-center">No ONU information available for this service.</p>
      </div>
      <div v-else>
        <!-- ONU Details Section -->
        <OnuDetails
          :onuDetails="onuDetails"
          :onuFullStatus="onuFullStatus"
        />

        <!-- Signal and Traffic Tabs -->
        <div class="border-t border-gray-200 px-6 py-4">
          <div class="flex border-b">
            <button
              @click="activeTab = 'signal'"
              :class="[
                'py-2 px-4 focus:outline-none',
                activeTab === 'signal' ? 'border-b-2 border-primary-500 font-bold' : ''
              ]"
            >
              Signal
            </button>
            <button
              @click="activeTab = 'traffic'"
              :class="[
                'py-2 px-4 focus:outline-none',
                activeTab === 'traffic' ? 'border-b-2 border-primary-500 font-bold' : ''
              ]"
            >
              Traffic
            </button>
            <button
              @click="activeTab = 'config'"
              :class="[
                'py-2 px-4 focus:outline-none',
                activeTab === 'config' ? 'border-b-2 border-primary-500 font-bold' : ''
              ]"
            >
              Configuration
            </button>
            <button
              @click="activeTab = 'history'"
              :class="[
                'py-2 px-4 focus:outline-none',
                activeTab === 'history' ? 'border-b-2 border-primary-500 font-bold' : ''
              ]"
            >
              History
            </button>
          </div>

          <!-- Signal Tab Content -->
          <OnuSignalTab
            v-if="activeTab === 'signal'"
            :onuStatus="onuStatus"
            :onuFullStatus="onuFullStatus"
            :loading="loadingSignal"
            :error="signalError"
            :signalGraphUrl="signalGraphUrl"
            @fetch-signal-graph="fetchSignalGraph"
          />

          <!-- Traffic Tab Content -->
          <OnuTrafficTab
            v-if="activeTab === 'traffic'"
            :onuStatus="onuStatus"
            :loading="loadingTraffic"
            :error="trafficError"
            :trafficGraphUrl="trafficGraphUrl"
            :trafficGraphKey="trafficGraphKey"
            :selectedGraphType="selectedGraphType"
            :autoRefreshEnabled="autoRefreshEnabled"
            @graph-type-change="onGraphTypeChange"
            @start-auto-refresh="startAutoRefresh"
            @stop-auto-refresh="stopAutoRefresh"
          />

          <!-- Configuration Tab Content -->
          <OnuConfigTab
            v-if="activeTab === 'config'"
            :onuFullStatus="onuFullStatus"
            :onuConfig="onuConfig"
            :loading="loadingConfig"
            :error="configError"
          />

          <!-- History Tab Content -->
          <OnuHistoryTab
            v-if="activeTab === 'history'"
            :onuFullStatus="onuFullStatus"
            :loading="loading"
            :error="error"
          />
        </div>

        <!-- Actions Section -->
        <OnuActions
          :onuDetails="onuDetails"
          :actionInProgress="actionInProgress"
          @reboot="rebootOnu"
          @reset="resetOnu"
          @enable="enableOnu"
          @disable="disableOnu"
          @enable-catv="enableCatv"
          @disable-catv="disableCatv"
        />
      </div>
    </Card>
  </div>
</template>

<script>
import OnuDetails from './OnuDetails'
import OnuSignalTab from './OnuSignalTab'
import OnuTrafficTab from './OnuTrafficTab'
import OnuConfigTab from './OnuConfigTab'
import OnuHistoryTab from './OnuHistoryTab'
import OnuActions from './OnuActions'

export default {
  components: {
    OnuDetails,
    OnuSignalTab,
    OnuTrafficTab,
    OnuConfigTab,
    OnuHistoryTab,
    OnuActions
  },
  props: {
    resourceId: {
      type: String,
      required: false
    },
  },
  data() {
    return {
      loading: true,
      error: null,
      onuDetails: null,
      onuStatus: null,
      onuConfig: null,
      onuFullStatus: null,
      catvStatus: null,
      activeTab: 'signal',
      loadingSignal: false,
      loadingTraffic: false,
      loadingConfig: false,
      signalError: null,
      trafficError: null,
      configError: null,
      actionInProgress: false,
      // Traffic graph properties
      selectedGraphType: 'hourly',
      trafficGraphUrl: null,
      trafficGraphKey: 0, // Used to force image refresh
      autoRefreshEnabled: false,
      refreshInterval: null,
      // Signal graph properties
      signalGraphUrl: null,
      // API throttling properties
      lastTrafficGraphCall: 0,
      lastSignalGraphCall: 0,
      lastStatusCall: 0,
      minApiCallInterval: 1000 // Minimum 1 second between API calls
    }
  },

  watch: {
    activeTab(newTab, oldTab) {
      // Fetch the appropriate graph when the tab is changed
      if (newTab === 'traffic' && (!this.trafficGraphUrl || oldTab !== 'traffic')) {
        this.fetchTrafficGraph()
      } else if (newTab === 'signal' && !this.signalGraphUrl) {
        this.fetchSignalGraph()
      }

      // Stop auto-refresh when switching away from traffic tab
      if (oldTab === 'traffic' && this.autoRefreshEnabled) {
        this.stopAutoRefresh()
      }
    }
  },

  mounted() {
    this.fetchOnuDetails()

    // Set initial tab to signal and fetch signal graph
    this.$nextTick(() => {
      if (this.resourceId && this.activeTab === 'signal') {
        this.fetchSignalGraph()
      }
    })
  },

  beforeUnmount() {
    // Clean up any intervals when component is destroyed
    this.stopAutoRefresh()
  },

  methods: {
    async fetchOnuDetails() {
      // Check if enough time has passed since the last call
      const now = Date.now()
      const timeSinceLastCall = now - this.lastStatusCall

      if (timeSinceLastCall < this.minApiCallInterval) {
        // Wait for the remaining time before making the call
        const waitTime = this.minApiCallInterval - timeSinceLastCall
        await new Promise(resolve => setTimeout(resolve, waitTime))
      }

      this.loading = true
      this.error = null
      this.loadingSignal = true
      this.signalError = null
      this.loadingConfig = true
      this.configError = null

      try {
        // Update the last call timestamp
        this.lastStatusCall = Date.now()

        // Get all ONU information from a single API call
        const response = await Nova.request().get(`/nova-vendor/smartolt/onu/${this.resourceId}/status`)
        this.onuFullStatus = response.data

        // Extract details from the full status
        const catvInfo = this.onuFullStatus.catv || {}
        this.catvStatus = catvInfo.value || catvInfo.message || 'N/A'

        if (this.onuFullStatus.details) {
          this.onuDetails = {
            sn: this.onuFullStatus.details['Serial number'] || '',
            status: this.onuFullStatus.details['ONU Status']?.toLowerCase() === 'enable' ? 'online' : 'offline',
            type: this.onuFullStatus.details['Type'] || '',
            board: this.onuFullStatus.details['Current channel'] || '',
            port: '', // This might be available in a different format
            onu: '' // This might be available in a different format
          }
        } else {
          this.onuDetails = this.onuDetails || {
            sn: '',
            status: 'offline',
            type: '',
            board: '',
            port: '',
            onu: ''
          }
        }

        if (this.onuDetails) {
          this.onuDetails.catv = this.catvStatus
          this.onuDetails.catvStatus = catvInfo.status ?? null
          this.onuDetails.catvError = catvInfo.error ?? null
        }

        // Extract status information
        if (this.onuFullStatus.optical) {
          this.onuStatus = {
            rx_power: this.onuFullStatus.optical['ONU Rx']?.replace('(dbm)', '') || 'N/A',
            tx_power: this.onuFullStatus.optical['ONU Tx']?.replace('(dbm)', '') || 'N/A',
            download: 'N/A', // This might be available in a different format
            upload: 'N/A' // This might be available in a different format
          }
        }

        // Extract configuration information
        if (this.onuFullStatus.config) {
          this.onuConfig = {
            speed_profile: 'N/A', // This might be available in a different format
            vlan_id: this.onuFullStatus.config.vlan && Object.values(this.onuFullStatus.config.vlan)[0]?.['VLAN-ID'] || 'N/A',
            wan_mode: 'N/A' // This might be available in a different format
          }
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to load ONU information'
        this.signalError = this.error
        this.configError = this.error
      } finally {
        this.loading = false
        this.loadingSignal = false
        this.loadingConfig = false
      }
    },

    async rebootOnu() {
      if (this.actionInProgress) return

      this.actionInProgress = true

      try {
        await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/reboot`)
        this.$toasted.show('ONU reboot initiated successfully', {type: 'success'})

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 5000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to reboot ONU', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    async resetOnu() {
      if (this.actionInProgress) return

      if (!confirm('Are you sure you want to reset this ONU to factory defaults?')) {
        return
      }

      this.actionInProgress = true

      try {
        await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/factory-reset`)
        this.$toasted.show('ONU factory reset initiated successfully', {type: 'success'})

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 5000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to reset ONU', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    async enableOnu() {
      if (this.actionInProgress) return

      this.actionInProgress = true

      try {
        const { data } = await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/enable`)
        this.handleCatvToast(data, 'ONU enabled successfully')

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 3000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to enable ONU', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    async disableOnu() {
      if (this.actionInProgress) return

      if (!confirm('Are you sure you want to disable this ONU?')) {
        return
      }

      this.actionInProgress = true

      try {
        const { data } = await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/disable`)
        this.handleCatvToast(data, 'ONU disabled successfully')

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 3000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to disable ONU', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    async enableCatv() {
      if (this.actionInProgress) return

      this.actionInProgress = true

      try {
        const { data } = await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/enable-catv`)
        this.handleCatvToast(data, 'CATV enabled successfully')

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 3000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to enable CATV', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    async disableCatv() {
      if (this.actionInProgress) return

      if (!confirm('Are you sure you want to disable CATV?')) {
        return
      }

      this.actionInProgress = true

      try {
        const { data } = await Nova.request().post(`/nova-vendor/smartolt/onu/${this.resourceId}/disable-catv`)
        this.handleCatvToast(data, 'CATV disabled successfully')

        // Refresh data after action
        setTimeout(() => {
          this.fetchOnuDetails()
        }, 3000)
      } catch (error) {
        this.$toasted.show(error.response?.data?.message || 'Failed to disable CATV', {type: 'error'})
      } finally {
        this.actionInProgress = false
      }
    },

    handleCatvToast(data, fallback = 'Operation completed successfully') {
      const baseMessage = data?.message || fallback
      const catvInfo = data?.catv || {}
      let toastType = 'success'
      let message = baseMessage

      if (catvInfo) {
        if (catvInfo.message || catvInfo.value) {
          message += ` | CATV: ${catvInfo.message || catvInfo.value}`
        }

        if (catvInfo.status === false && catvInfo.error) {
          toastType = 'warning'
          this.$toasted.show(`CATV: ${catvInfo.error}`, {type: 'error'})
        }
      }

      this.$toasted.show(message, {type: toastType})
    },

    /**
     * Handle graph type change from the traffic tab
     */
    onGraphTypeChange(graphType) {
      this.selectedGraphType = graphType
      this.fetchTrafficGraph()
    },

    /**
     * Fetch the traffic graph for the selected time period
     */
    async fetchTrafficGraph() {
      // Check if enough time has passed since the last call
      const now = Date.now()
      const timeSinceLastCall = now - this.lastTrafficGraphCall

      if (timeSinceLastCall < this.minApiCallInterval) {
        // If we're in auto-refresh mode, we can just skip this call
        if (this.autoRefreshEnabled) {
          console.log('Skipping traffic graph refresh due to throttling')
          return
        }

        // Otherwise, wait for the remaining time before making the call
        const waitTime = this.minApiCallInterval - timeSinceLastCall
        await new Promise(resolve => setTimeout(resolve, waitTime))
      }

      this.loadingTraffic = true
      this.trafficError = null

      try {
        // Update the last call timestamp
        this.lastTrafficGraphCall = Date.now()

        // Increment the key to force image refresh
        this.trafficGraphKey++

        // Set the traffic graph URL
        this.trafficGraphUrl = `/nova-vendor/smartolt/onu/${this.resourceId}/traffic-graph/${this.selectedGraphType}?t=${Date.now()}`

        // If auto-refresh is enabled and we're switching away from hourly, stop it
        if (this.autoRefreshEnabled && this.selectedGraphType !== 'hourly') {
          this.stopAutoRefresh()
        }
      } catch (error) {
        this.trafficError = error.response?.data?.message || 'Failed to load traffic graph'
        this.trafficGraphUrl = null
      } finally {
        this.loadingTraffic = false
      }
    },

    /**
     * Start auto-refreshing the traffic graph every 5 seconds
     */
    startAutoRefresh() {
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval)
      }

      this.autoRefreshEnabled = true
      this.refreshInterval = setInterval(() => {
        this.fetchTrafficGraph()
      }, 5000) // Refresh every 5 seconds
    },

    /**
     * Stop auto-refreshing the traffic graph
     */
    stopAutoRefresh() {
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval)
        this.refreshInterval = null
      }

      this.autoRefreshEnabled = false
    },

    /**
     * Fetch the signal graph
     */
    async fetchSignalGraph() {
      // Check if enough time has passed since the last call
      const now = Date.now()
      const timeSinceLastCall = now - this.lastSignalGraphCall

      if (timeSinceLastCall < this.minApiCallInterval) {
        // Wait for the remaining time before making the call
        const waitTime = this.minApiCallInterval - timeSinceLastCall
        await new Promise(resolve => setTimeout(resolve, waitTime))
      }

      this.loadingSignal = true
      this.signalError = null

      try {
        // Update the last call timestamp
        this.lastSignalGraphCall = Date.now()

        // Set the signal graph URL
        this.signalGraphUrl = `/nova-vendor/smartolt/onu/${this.resourceId}/signal-graph?t=${Date.now()}`
      } catch (error) {
        this.signalError = error.response?.data?.message || 'Failed to load signal graph'
        this.signalGraphUrl = null
      } finally {
        this.loadingSignal = false
      }
    }
  }
}
</script>
