<template>
  <div class="py-4">
    <div v-if="loading" class="flex justify-center items-center p-4">
      <Loader class="text-60"/>
    </div>
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
      <p>{{ error }}</p>
    </div>
    <div v-else>
      <!-- LAN Interfaces -->
      <div v-if="onuFullStatus?.config?.interfaces" class="mb-6">
        <h4 class="text-lg font-bold mb-2">LAN Interfaces</h4>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Port</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speed</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin State</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Frame Size</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(interfaceItem, port) in onuFullStatus.config.interfaces" :key="port">
                <td class="py-2 px-4 border-b border-gray-200">{{ port }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ interfaceItem.Speed }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ interfaceItem['Admin state'] }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ interfaceItem['Max-frame size'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- VLAN Configuration -->
      <div v-if="onuFullStatus?.config?.vlan" class="mb-6">
        <h4 class="text-lg font-bold mb-2">VLAN Configuration</h4>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interface</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VLAN ID</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default Priority</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VLAN List</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(vlan, interfaceName) in onuFullStatus.config.vlan" :key="interfaceName">
                <td class="py-2 px-4 border-b border-gray-200">{{ interfaceName }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ vlan.Mode }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ vlan['VLAN-ID'] }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ vlan['Def-Prio'] }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ vlan['Vlan-list'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- MAC Addresses -->
      <div v-if="onuFullStatus?.interfaces?.macs" class="mb-6">
        <h4 class="text-lg font-bold mb-2">MAC Addresses</h4>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MAC Address</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VLAN</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Port</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(mac, index) in onuFullStatus.interfaces.macs" :key="index">
                <td class="py-2 px-4 border-b border-gray-200">{{ mac['MAC address'] }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ mac.Vlan }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ mac.Type }}</td>
                <td class="py-2 px-4 border-b border-gray-200">{{ mac.Port }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Basic Configuration -->
      <div class="mb-4">
        <p><span class="font-bold">Speed Profile:</span> {{ onuConfig?.speed_profile || 'N/A' }}</p>
        <p><span class="font-bold">VLAN ID:</span> {{ onuConfig?.vlan_id || 'N/A' }}</p>
        <p><span class="font-bold">WAN Mode:</span> {{ onuConfig?.wan_mode || 'N/A' }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    onuFullStatus: {
      type: Object,
      required: true
    },
    onuConfig: {
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
    }
  }
}
</script>
