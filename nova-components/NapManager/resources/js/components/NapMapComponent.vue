<script>
import { defineComponent, ref, onMounted, defineProps } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: "NapMapComponent",
  props: {
    googleMapsApiKey: {
      type: String,
      required: true
    }
  },

  setup(props) {
    const mapLoaded = ref(false)
    const mapInstance = ref(null)
    const markers = ref([])
    const napBoxes = ref([])
    const loading = ref(true)
    const error = ref(null)

    // Load Google Maps API
    const loadGoogleMapsApi = () => {
      return new Promise((resolve, reject) => {
        if (window.google && window.google.maps) {
          resolve(window.google.maps)
          return
        }

        const script = document.createElement('script')
        script.src = `https://maps.googleapis.com/maps/api/js?key=${props.googleMapsApiKey}&libraries=places`
        script.async = true
        script.defer = true

        script.onload = () => {
          mapLoaded.value = true
          resolve(window.google.maps)
        }

        script.onerror = (error) => {
          reject(error)
        }

        document.head.appendChild(script)
      })
    }

    // Initialize map
    const initMap = async () => {
      try {
        const maps = await loadGoogleMapsApi()

        // Create map instance
        mapInstance.value = new maps.Map(document.getElementById('map-container'), {
          center: { lat: 4.6097, lng: -74.0817 }, // Default to Bogotá, Colombia
          zoom: 12,
          mapTypeId: maps.MapTypeId.ROADMAP,
          mapTypeControl: true,
          streetViewControl: true,
          fullscreenControl: true
        })

        // Load NAP boxes data
        await fetchNapBoxes()

      } catch (err) {
        error.value = 'Error loading Google Maps: ' + err.message
        console.error('Error loading Google Maps:', err)
      }
    }

    // Fetch NAP boxes from API
    const fetchNapBoxes = async () => {
      try {
        loading.value = true
        const response = await axios.get('/nova-vendor/nap-manager/map-data')
        napBoxes.value = response.data

        // Add markers for each NAP box
        addMarkers()

        loading.value = false
      } catch (err) {
        error.value = 'Error fetching NAP boxes: ' + err.message
        console.error('Error fetching NAP boxes:', err)
        loading.value = false
      }
    }

    // Add markers to the map
    const addMarkers = () => {
      if (!mapInstance.value || !napBoxes.value.length) return

      const bounds = new google.maps.LatLngBounds()

      // Clear existing markers
      markers.value.forEach(marker => marker.setMap(null))
      markers.value = []

      // Add new markers
      napBoxes.value.forEach(nap => {
        const position = { lat: nap.lat, lng: nap.lng }

        // Skip invalid coordinates
        if (!position.lat || !position.lng) return

        // Create marker
        const marker = new google.maps.Marker({
          position,
          map: mapInstance.value,
          title: nap.name,
          icon: getMarkerIcon(nap.status, nap.occupancy)
        })

        // Create info window
        const infoWindow = new google.maps.InfoWindow({
          content: `
            <div class="info-window">
              <h3>${nap.name} (${nap.code})</h3>
              <p><strong>Status:</strong> ${nap.status}</p>
              <p><strong>Occupancy:</strong> ${nap.occupancy}%</p>
              <p><strong>Available Ports:</strong> ${nap.available_ports}/${nap.total_capacity}</p>
              <p><strong>Address:</strong> ${nap.address}</p>
            </div>
          `
        })

        // Add click event to marker
        marker.addListener('click', () => {
          infoWindow.open(mapInstance.value, marker)
        })

        // Add marker to array
        markers.value.push(marker)

        // Extend bounds
        bounds.extend(position)
      })

      // Fit map to bounds if we have markers
      if (markers.value.length > 0) {
        mapInstance.value.fitBounds(bounds)
      }
    }

    // Get marker icon based on status and occupancy
    const getMarkerIcon = (status, occupancy) => {
      let color = '#4CAF50' // Green for active

      if (status === 'inactive') {
        color = '#9E9E9E' // Gray for inactive
      } else if (status === 'maintenance') {
        color = '#FFC107' // Yellow for maintenance
      } else if (status === 'damaged') {
        color = '#F44336' // Red for damaged
      } else if (occupancy > 90) {
        color = '#FF9800' // Orange for high occupancy
      }

      return {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: color,
        fillOpacity: 0.9,
        strokeWeight: 1,
        strokeColor: '#FFFFFF',
        scale: 10
      }
    }

    // Initialize map on component mount
    onMounted(() => {
      initMap()
    })

    return {
      mapLoaded,
      loading,
      error,
      napBoxes,
      refreshMap: fetchNapBoxes
    }
  }
})
</script>

<template>
  <div class="nap-map-container">
    <div class="map-controls nap-mb-4">
      <div class="nap-flex nap-justify-between nap-items-center">
        <h3 class="nap-text-xl nap-font-bold">NAP Boxes Map</h3>
        <button
          @click="refreshMap"
          class="btn btn-default btn-primary"
          :disabled="loading"
        >
          <span v-if="loading">Loading...</span>
          <span v-else>Refresh Map</span>
        </button>
      </div>
    </div>

    <div v-if="error" class="nap-bg-red-100 nap-border nap-border-red-400 nap-text-red-700 nap-px-4 nap-py-3 nap-rounded nap-mb-4">
      {{ error }}
    </div>

    <div id="map-container" class="map-container"></div>
  </div>
</template>

<style scoped>
.nap-map-container {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.map-container {
  flex-grow: 1;
  min-height: 500px;
  border-radius: 8px;
  overflow: hidden;
}

.legend-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 8px;
}

.info-window {
  padding: 8px;
  max-width: 300px;
}

.info-window h3 {
  margin-top: 0;
  margin-bottom: 8px;
  font-weight: bold;
}

.info-window p {
  margin: 4px 0;
}
</style>
