<script>
import { defineComponent, ref, onMounted, watch, h } from 'vue'
import axios from 'axios'
import {
  VueFlow,
  Background,
  MiniMap,
  Controls,
  useVueFlow,
  Panel,
  NodeTypes
} from '@vue-flow/core'
import '@vue-flow/core/dist/style.css'
import { useToast } from 'vue-toastification'

// Define custom NAP node component
const NapNode = {
  props: ['id', 'data', 'selected'],
  template: `
    <div class="nap-node" :class="['nap-node-' + data.status]">
      <div class="nap-node-header">
        <div class="nap-node-title">{{ data.label }}</div>
        <div class="nap-node-code">{{ data.code }}</div>
      </div>
      <div class="nap-node-content">
        <div class="nap-node-status">Status: {{ data.status }}</div>
        <div class="nap-node-occupancy">
          Occupancy: {{ data.occupancy }}%
          <div class="occupancy-bar">
            <div class="occupancy-fill" :style="{ width: data.occupancy + '%' }"></div>
          </div>
        </div>
        <div class="nap-node-level">Level: {{ data.level }}</div>
      </div>
    </div>
  `
}

export default defineComponent({
  name: "NapFlowComponent",
  components: {
    VueFlow,
    Background,
    MiniMap,
    Controls,
    Panel,
    NapNode
  },

  setup() {
    const loading = ref(true)
    const error = ref(null)
    const nodes = ref([])
    const edges = ref([])
    const toast = useToast()

    // Form state
    const showNapForm = ref(false)
    const showPortForm = ref(false)
    const isEditMode = ref(false)
    const selectedNapBox = ref(null)
    const selectedPort = ref(null)

    // NAP Box form data
    const napBoxForm = ref({
      name: '',
      code: '',
      address: '',
      latitude: 0,
      longitude: 0,
      status: 'active',
      capacity: 8,
      technology_type: 'fiber',
      installation_date: new Date().toISOString().split('T')[0],
      brand: '',
      model: '',
      parent_nap_id: null
    })

    // Port form data
    const portForm = ref({
      port_number: 1,
      port_name: '',
      status: 'available',
      connection_type: 'fiber',
      technician_notes: ''
    })

    // Define node types
    const nodeTypes = ref({
      napNode: NapNode
    })

    // Define default edge options
    const defaultEdgeOptions = ref({
      type: 'smoothstep',
      animated: true
    })

    // Get node color based on status
    const getNodeColor = (status) => {
      switch (status) {
        case 'active': return '#4CAF50'
        case 'inactive': return '#9E9E9E'
        case 'maintenance': return '#FFC107'
        case 'damaged': return '#F44336'
        default: return '#2196F3'
      }
    }

    // Fetch flow data from API
    const fetchFlowData = async () => {
      try {
        loading.value = true
        const response = await axios.get('/nova-vendor/nap-manager/flow-data')
        nodes.value = response.data.nodes
        edges.value = response.data.edges
        loading.value = false
      } catch (err) {
        error.value = 'Error fetching flow data: ' + err.message
        console.error('Error fetching flow data:', err)
        loading.value = false
      }
    }

    // Handle node drag stop event
    const onNodeDragEnd = async (event) => {
      try {
        const node = event.node
        // Update node position in database
        await axios.post(`/nova-vendor/nap-manager/update-node-position/${node.id}`, {
          x: node.position.x,
          y: node.position.y
        })
      } catch (err) {
        console.error('Error updating node position:', err)
      }
    }

    // Handle connect event
    const onEdgeConnect = async (params) => {
      try {
        // Update connection in database
        await axios.post('/nova-vendor/nap-manager/update-connection', {
          source: params.source,
          target: params.target
        })

        // Refresh flow data
        await fetchFlowData()
      } catch (err) {
        console.error('Error updating connection:', err)
      }
    }

    // Handle node click event
    const onNodeClick = (event) => {
      console.log('Node clicked:', event.node)
      // Get the NAP box details for the clicked node
      const napBoxId = event.node.id
      fetchNapBoxDetails(napBoxId)
    }

    // Fetch NAP box details
    const fetchNapBoxDetails = async (napBoxId) => {
      try {
        const response = await axios.get(`/nova-vendor/nap-manager/nap-box/${napBoxId}`)
        selectedNapBox.value = response.data
      } catch (err) {
        console.error('Error fetching NAP box details:', err)
        toast.error('Error fetching NAP box details')
      }
    }

    // Get user's current location
    const getUserLocation = () => {
      return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
          toast.warning('Geolocation is not supported by your browser')
          resolve({ latitude: 0, longitude: 0 })
          return
        }

        navigator.geolocation.getCurrentPosition(
          (position) => {
            resolve({
              latitude: position.coords.latitude,
              longitude: position.coords.longitude
            })
          },
          (error) => {
            console.error('Error getting user location:', error)
            toast.warning('Could not get your location: ' + error.message)
            resolve({ latitude: 0, longitude: 0 })
          },
          { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
        )
      })
    }

    // Open NAP box form for creating a new NAP box
    const openCreateNapForm = async () => {
      isEditMode.value = false

      // Get user's current location
      const location = await getUserLocation()

      napBoxForm.value = {
        name: '',
        code: '',
        address: '',
        latitude: location.latitude,
        longitude: location.longitude,
        status: 'active',
        capacity: 8,
        technology_type: 'fiber',
        installation_date: new Date().toISOString().split('T')[0],
        brand: '',
        model: '',
        parent_nap_id: null
      }
      showNapForm.value = true
    }

    // Open NAP box form for editing an existing NAP box
    const openEditNapForm = (napBox) => {
      isEditMode.value = true
      napBoxForm.value = { ...napBox }
      showNapForm.value = true
    }

    // Close NAP box form
    const closeNapForm = () => {
      showNapForm.value = false
      selectedNapBox.value = null
    }

    // Submit NAP box form
    const submitNapForm = async () => {
      try {
        loading.value = true

        if (isEditMode.value) {
          // Update existing NAP box
          await axios.put(`/nova-vendor/nap-manager/nap-box/${napBoxForm.value.id}`, napBoxForm.value)
          toast.success('NAP box updated successfully')
        } else {
          // Create new NAP box
          await axios.post('/nova-vendor/nap-manager/nap-box', napBoxForm.value)
          toast.success('NAP box created successfully')
        }

        // Refresh flow data
        await fetchFlowData()
        closeNapForm()
      } catch (err) {
        console.error('Error submitting NAP box form:', err)
        toast.error('Error saving NAP box: ' + (err.response?.data?.message || err.message))
      } finally {
        loading.value = false
      }
    }

    // Delete NAP box
    const deleteNapBox = async (napBoxId) => {
      if (!confirm('Are you sure you want to delete this NAP box? This will also delete all associated ports.')) {
        return
      }

      try {
        loading.value = true
        await axios.delete(`/nova-vendor/nap-manager/nap-box/${napBoxId}`)
        toast.success('NAP box deleted successfully')
        await fetchFlowData()
        selectedNapBox.value = null
      } catch (err) {
        console.error('Error deleting NAP box:', err)
        toast.error('Error deleting NAP box: ' + (err.response?.data?.message || err.message))
      } finally {
        loading.value = false
      }
    }

    // Open port form for creating a new port
    const openCreatePortForm = (napBoxId) => {
      isEditMode.value = false
      portForm.value = {
        nap_box_id: napBoxId,
        port_number: 1,
        port_name: '',
        status: 'available',
        connection_type: 'fiber',
        technician_notes: ''
      }
      showPortForm.value = true
    }

    // Open port form for editing an existing port
    const openEditPortForm = async (portId) => {
      try {
        const response = await axios.get(`/nova-vendor/nap-manager/port/${portId}`)
        isEditMode.value = true
        portForm.value = response.data
        showPortForm.value = true
      } catch (err) {
        console.error('Error fetching port details:', err)
        toast.error('Error fetching port details')
      }
    }

    // Close port form
    const closePortForm = () => {
      showPortForm.value = false
      selectedPort.value = null
    }

    // Submit port form
    const submitPortForm = async () => {
      try {
        loading.value = true

        if (isEditMode.value) {
          // Update existing port
          await axios.put(`/nova-vendor/nap-manager/port/${portForm.value.id}`, portForm.value)
          toast.success('Port updated successfully')
        } else {
          // Create new port
          await axios.post('/nova-vendor/nap-manager/port', portForm.value)
          toast.success('Port created successfully')
        }

        // Refresh NAP box details
        if (selectedNapBox.value) {
          await fetchNapBoxDetails(selectedNapBox.value.id)
        }

        closePortForm()
      } catch (err) {
        console.error('Error submitting port form:', err)
        toast.error('Error saving port: ' + (err.response?.data?.message || err.message))
      } finally {
        loading.value = false
      }
    }

    // Delete port
    const deletePort = async (portId) => {
      if (!confirm('Are you sure you want to delete this port?')) {
        return
      }

      try {
        loading.value = true
        await axios.delete(`/nova-vendor/nap-manager/port/${portId}`)
        toast.success('Port deleted successfully')

        // Refresh NAP box details
        if (selectedNapBox.value) {
          await fetchNapBoxDetails(selectedNapBox.value.id)
        }
      } catch (err) {
        console.error('Error deleting port:', err)
        toast.error('Error deleting port: ' + (err.response?.data?.message || err.message))
      } finally {
        loading.value = false
      }
    }

    // Initialize on component mount
    onMounted(() => {
      fetchFlowData()
    })

    return {
      loading,
      error,
      nodes,
      edges,
      nodeTypes,
      defaultEdgeOptions,
      getNodeColor,
      onNodeClick,
      onNodeDragEnd,
      onEdgeConnect,
      refreshFlow: fetchFlowData,

      // NAP box form
      showNapForm,
      napBoxForm,
      isEditMode,
      openCreateNapForm,
      openEditNapForm,
      closeNapForm,
      submitNapForm,
      deleteNapBox,

      // Port form
      showPortForm,
      portForm,
      openCreatePortForm,
      openEditPortForm,
      closePortForm,
      submitPortForm,
      deletePort,

      // Selected items
      selectedNapBox,
      selectedPort
    }
  }
})
</script>

<template>
  <div class="nap-flow-container">
    <div class="flow-controls nap-mb-4">
      <div class="nap-flex nap-justify-between nap-items-center">
        <h3 class="nap-text-xl nap-font-bold">NAP Distribution Flow</h3>
        <div class="nap-flex nap-space-x-2">
          <button
            @click="openCreateNapForm"
            class="btn btn-default btn-success"
            :disabled="loading"
          >
            <span>Add NAP Box</span>
          </button>
          <button
            @click="refreshFlow"
            class="btn btn-default btn-primary"
            :disabled="loading"
          >
            <span v-if="loading">Loading...</span>
            <span v-else>Refresh Flow</span>
          </button>
        </div>
      </div>
    </div>

    <!-- NAP Box Form Modal -->
    <div v-if="showNapForm" class="nap-fixed nap-inset-0 nap-bg-black nap-bg-opacity-50 nap-flex nap-items-center nap-justify-center nap-z-50">
      <div class="nap-bg-white nap-rounded-lg nap-p-6 nap-w-full nap-max-w-2xl nap-max-h-screen nap-overflow-y-auto">
        <div class="nap-flex nap-justify-between nap-items-center nap-mb-4">
          <h3 class="nap-text-xl nap-font-bold">{{ isEditMode ? 'Edit NAP Box' : 'Create NAP Box' }}</h3>
          <button @click="closeNapForm" class="nap-text-gray-500 hover:nap-text-gray-700">
            <span class="nap-text-2xl">&times;</span>
          </button>
        </div>

        <form @submit.prevent="submitNapForm" class="nap-space-y-4">
          <div class="nap-grid nap-grid-cols-1 md:nap-grid-cols-2 nap-gap-4">
            <!-- Name -->
            <div>
              <label class="nap-block nap-text-sm nap-font-medium nap-text-gray-700">Name</label>
              <input
                type="text"
                v-model="napBoxForm.name"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Code -->
            <div>
              <label class="nap-block nap-text-sm nap-font-medium nap-text-gray-700">Code</label>
              <input
                type="text"
                v-model="napBoxForm.code"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Address -->
            <div class="md:nap-col-span-2">
              <label class="nap-block nap-text-sm nap-font-medium nap-text-gray-700">Address</label>
              <textarea
                v-model="napBoxForm.address"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
                rows="2"
              ></textarea>
            </div>

            <!-- Latitude -->
            <div>
              <label class="nap-block nap-text-sm nap-font-medium nap-text-gray-700">Latitude</label>
              <input
                type="number"
                v-model="napBoxForm.latitude"
                step="0.00000001"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Longitude -->
            <div>
              <label class="nap-block nap-text-sm nap-font-medium nap-text-gray-700">Longitude</label>
              <input
                type="number"
                v-model="napBoxForm.longitude"
                step="0.00000001"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="napBoxForm.status"
                required
                class="nap-bg-gray-50 nap-border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9]"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
                <option value="damaged">Damaged</option>
              </select>
            </div>

            <!-- Capacity -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Capacity</label>
              <input
                type="number"
                v-model="napBoxForm.capacity"
                min="1"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Technology Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Technology Type</label>
              <select
                v-model="napBoxForm.technology_type"
                required
                class="nap-bg-gray-50 nap-border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] nap-outline-none"
              >
                <option value="fiber">Fiber</option>
                <option value="coaxial">Coaxial</option>
                <option value="ftth">FTTH</option>
                <option value="mixed">Mixed</option>
              </select>
            </div>

            <!-- Installation Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Installation Date</label>
              <input
                type="date"
                v-model="napBoxForm.installation_date"
                required
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Brand -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Brand</label>
              <input
                type="text"
                v-model="napBoxForm.brand"
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>

            <!-- Model -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Model</label>
              <input
                type="text"
                v-model="napBoxForm.model"
                class="nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
              />
            </div>
          </div>

          <div class="flex justify-end space-x-2 pt-4">
            <button
              type="button"
              @click="closeNapForm"
              class="nap-px-4 nap-py-2 nap-bg-gray-200 nap-text-gray-800 nap-rounded-md hover:nap-bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 nap-bg-blue-600 nap-text-white rounded-md hover:nap-bg-blue-700"
              :disabled="loading"
            >
              {{ loading ? 'Saving...' : (isEditMode ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Port Form Modal -->
    <div v-if="showPortForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-lg max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold">{{ isEditMode ? 'Edit Port' : 'Create Port' }}</h3>
          <button @click="closePortForm" class="text-gray-500 hover:text-gray-700">
            <span class="text-2xl">&times;</span>
          </button>
        </div>

        <form @submit.prevent="submitPortForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Port Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Port Number</label>
              <input
                type="number"
                v-model="portForm.port_number"
                min="1"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>

            <!-- Port Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Port Name (Optional)</label>
              <input
                type="text"
                v-model="portForm.port_name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              />
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="portForm.status"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="damaged">Damaged</option>
                <option value="maintenance">Maintenance</option>
                <option value="reserved">Reserved</option>
                <option value="testing">Testing</option>
              </select>
            </div>

            <!-- Connection Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Connection Type</label>
              <select
                v-model="portForm.connection_type"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
                <option value="fiber">Fiber</option>
                <option value="coaxial">Coaxial</option>
                <option value="ethernet">Ethernet</option>
                <option value="mixed">Mixed</option>
              </select>
            </div>

            <!-- Technician Notes -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Technician Notes</label>
              <textarea
                v-model="portForm.technician_notes"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                rows="3"
              ></textarea>
            </div>
          </div>

          <div class="flex justify-end space-x-2 pt-4">
            <button
              type="button"
              @click="closePortForm"
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="loading"
            >
              {{ loading ? 'Saving...' : (isEditMode ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <div class="flex flex-col md:flex-row gap-4">
      <!-- Flow diagram -->
      <div class="flex-grow">
        <div v-if="loading && !error" class="flex justify-center items-center" style="height: 400px;">
          <div class="spinner"></div>
          <span class="ml-2">Loading flow data...</span>
        </div>

        <div v-else class="flow-container">
          <VueFlow
            v-model="nodes"
            v-model:edges="edges"
            :node-types="nodeTypes"
            :default-edge-options="defaultEdgeOptions"
            fit-view-on-init
            :min-zoom="0.2"
            :max-zoom="4"
            @nodeclick="onNodeClick"
            @nodedragstop="onNodeDragEnd"
            @connect="onEdgeConnect"
          >
            <Background pattern="dots" :size="1.5" :gap="20" />
            <MiniMap :node-color="getNodeColor" />
            <Controls />
            <Panel position="top-right" class="custom-controls">
              <button @click="refreshFlow" class="btn-refresh">
                <span>Refresh</span>
              </button>
            </Panel>
          </VueFlow>
        </div>
      </div>

      <!-- NAP Box Details Panel -->
      <div v-if="selectedNapBox" class="w-full md:w-96 bg-white p-4 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">NAP Box Details</h3>
          <div class="flex space-x-2">
            <button
              @click="openEditNapForm(selectedNapBox)"
              class="px-2 py-1 bg-[#0ea5e9] text-white rounded-md hover:bg-blue-600 text-sm"
            >
              Edit
            </button>
            <button
              @click="deleteNapBox(selectedNapBox.id)"
              class="px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
            >
              Delete
            </button>
          </div>
        </div>

        <div class="mb-4">
          <div class="grid grid-cols-2 gap-2">
            <div class="text-sm font-medium text-gray-500">Name:</div>
            <div class="text-sm">{{ selectedNapBox.name }}</div>

            <div class="text-sm font-medium text-gray-500">Code:</div>
            <div class="text-sm">{{ selectedNapBox.code }}</div>

            <div class="text-sm font-medium text-gray-500">Status:</div>
            <div class="text-sm">
              <span
                :class="{
                  'text-green-600': selectedNapBox.status === 'active',
                  'text-gray-600': selectedNapBox.status === 'inactive',
                  'text-yellow-600': selectedNapBox.status === 'maintenance',
                  'text-red-600': selectedNapBox.status === 'damaged'
                }"
              >
                {{ selectedNapBox.status }}
              </span>
            </div>

            <div class="text-sm font-medium text-gray-500">Capacity:</div>
            <div class="text-sm">{{ selectedNapBox.capacity }}</div>

            <div class="text-sm font-medium text-gray-500">Technology:</div>
            <div class="text-sm">{{ selectedNapBox.technology_type }}</div>

            <div class="text-sm font-medium text-gray-500">Installation Date:</div>
            <div class="text-sm">{{ selectedNapBox.installation_date }}</div>
          </div>
        </div>

        <!-- Ports Section -->
        <div>
          <div class="flex justify-between items-center mb-2">
            <h4 class="text-md font-bold">Ports</h4>
            <button
              @click="openCreatePortForm(selectedNapBox.id)"
              class="px-2 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm"
            >
              Add Port
            </button>
          </div>

          <div v-if="selectedNapBox.ports && selectedNapBox.ports.length > 0" class="space-y-2">
            <div v-for="port in selectedNapBox.ports" :key="port.id" class="border rounded-md p-2">
              <div class="flex justify-between items-center">
                <div>
                  <span class="font-medium">Port {{ port.port_number }}</span>
                  <span v-if="port.port_name" class="text-sm text-gray-500 ml-1">({{ port.port_name }})</span>
                </div>
                <div class="flex space-x-1">
                  <button
                    @click="openEditPortForm(port.id)"
                    class="px-1.5 py-0.5 bg-[#0ea5e9] text-white rounded hover:bg-blue-600 text-xs"
                  >
                    Edit
                  </button>
                  <button
                    @click="deletePort(port.id)"
                    class="px-1.5 py-0.5 bg-red-500 text-white rounded hover:bg-red-600 text-xs"
                  >
                    Delete
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-1 mt-1 text-xs">
                <div class="text-gray-500">Status:</div>
                <div>
                  <span
                    :class="{
                      'text-green-600': port.status === 'available',
                      'text-blue-600': port.status === 'occupied',
                      'text-yellow-600': port.status === 'maintenance' || port.status === 'testing',
                      'text-red-600': port.status === 'damaged',
                      'text-purple-600': port.status === 'reserved'
                    }"
                  >
                    {{ port.status }}
                  </span>
                </div>
                <div class="text-gray-500">Connection:</div>
                <div>{{ port.connection_type }}</div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-4 text-gray-500">
            No ports added yet. Click "Add Port" to create one.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.nap-flow-container {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.flow-container {
  flex-grow: 1;
  min-height: 500px;
  border-radius: 8px;
  overflow: hidden;
  background-color: #f5f5f5;
}

.legend-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 8px;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #09f;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* NAP Node Styles */
:deep(.nap-node) {
  padding: 10px;
  border-radius: 5px;
  width: 200px;
  background-color: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

:deep(.nap-node-active) {
  border-left: 5px solid #4CAF50;
}

:deep(.nap-node-inactive) {
  border-left: 5px solid #9E9E9E;
}

:deep(.nap-node-maintenance) {
  border-left: 5px solid #FFC107;
}

:deep(.nap-node-damaged) {
  border-left: 5px solid #F44336;
}

:deep(.nap-node-header) {
  border-bottom: 1px solid #eee;
  padding-bottom: 8px;
  margin-bottom: 8px;
}

:deep(.nap-node-title) {
  font-weight: bold;
  font-size: 14px;
}

:deep(.nap-node-code) {
  font-size: 12px;
  color: #666;
}

:deep(.nap-node-content) {
  font-size: 12px;
}

:deep(.nap-node-status) {
  margin-bottom: 5px;
}

:deep(.nap-node-occupancy) {
  margin-bottom: 5px;
}

:deep(.nap-node-level) {
  color: #666;
}

:deep(.occupancy-bar) {
  height: 5px;
  background-color: #eee;
  border-radius: 2px;
  margin-top: 2px;
}

:deep(.occupancy-fill) {
  height: 100%;
  background-color: #2196F3;
  border-radius: 2px;
}

/* Custom Controls Styles */
:deep(.custom-controls) {
  background-color: white;
  padding: 5px;
  border-radius: 4px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

:deep(.btn-refresh) {
  background-color: #2196F3;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 5px 10px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.3s;
}

:deep(.btn-refresh:hover) {
  background-color: #0b7dda;
}
</style>
