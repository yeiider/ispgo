<template>
  <div class="olt-detail-container">
    <div class="header">
      <h2 class="title">OLT Details</h2>
      <button class="btn btn-secondary" @click="goBack">Back to OLT List</button>
    </div>

    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Loading OLT details...</p>
    </div>

    <div v-else>
      <!-- OLT Cards Section -->
      <div class="section">
        <h3 class="section-title">OLT Cards</h3>
        <div class="cards-grid">
          <div v-for="(card, index) in oltCards" :key="index" class="card">
            <div class="card-header">
              <div class="card-title">
                <span class="slot-label">Slot {{ card.slot }}</span>
                <span class="card-type">{{ card.type }}</span>
              </div>
              <div :class="['status-badge', card.status === 'Online' ? 'status-online' : 'status-offline']">
                {{ card.status }}
              </div>
            </div>
            <div class="card-body">
              <div class="info-row">
                <strong>Real Type:</strong> {{ card.real_type }}
              </div>
              <div class="info-row">
                <strong>Ports:</strong> {{ card.ports }}
              </div>
              <div class="info-row">
                <strong>Software Version:</strong> {{ card.software_version }}
              </div>
              <div class="info-row">
                <strong>Role:</strong> {{ card.role }}
              </div>
              <div class="info-row">
                <strong>Last Updated:</strong> {{ card.info_updated }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Unconfigured ONUs Section -->
      <div class="section">
        <h3 class="section-title">Unconfigured ONUs</h3>
        <div v-if="unconfiguredOnus.length === 0" class="empty-state">
          <p>No unconfigured ONUs found for this OLT.</p>
        </div>
        <div v-else class="table-container">
          <table class="onu-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>PON Type</th>
              <th>Board</th>
              <th>Port</th>
              <th>ONU</th>
              <th>Serial Number</th>
              <th>ONU Type</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="onu in unconfiguredOnus" :key="onu.id">
              <td>{{ onu.id }}</td>
              <td>{{ onu.pon_type }}</td>
              <td>{{ onu.board }}</td>
              <td>{{ onu.port }}</td>
              <td>{{ onu.onu }}</td>
              <td>{{ onu.sn }}</td>
              <td>{{ onu.onu_type_name }}</td>
              <td>
                <button
                  v-if="onu.actions.includes('authorize')"
                  class="btn btn-primary"
                  @click="authorizeOnu(onu)"
                >
                  Authorize
                </button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- ───────── Modal de autorización ───────── -->
  <div v-if="showModal" class="modal-overlay">
    <div class="modal">
      <h3 class="modal-title">
        Autorizar ONU {{ selectedOnu?.sn }}
      </h3>

      <!-- Buscador -->
      <label class="modal-label">Buscar servicio</label>
      <input
        type="text"
        class="modal-input"
        v-model="search"
        placeholder="Escriba al menos 2 caracteres…"
      />

      <!-- Lista de resultados -->
      <div v-if="servicesLoading" class="loading-small">Cargando…</div>

      <ul v-else class="services-list">
        <li
          v-for="srv in services"
          :key="srv.id"
          :class="{ selected: srv.id === selectedService?.id }"
          @click="selectedService = srv"
        >
          {{ srv.name }}
        </li>
        <li v-if="!servicesLoading && services.length === 0 && search.length >= 2">
          Sin resultados…
        </li>
      </ul>

      <!-- ONU Mode Selection -->
      <div class="onu-mode-selection">
        <label class="modal-label">ONU Mode</label>
        <div class="radio-group">
          <label>
            <input type="radio" v-model="onuMode" value="Routing">
            Routing
          </label>
          <label>
            <input type="radio" v-model="onuMode" value="Bridging">
            Bridging
          </label>
        </div>
      </div>

      <!-- Zone Selection -->
      <div class="zone-selection">
        <label class="modal-label">Zone</label>
        <select v-model="selectedZone" class="modal-input">
          <option value="">Select a zone</option>
          <option v-for="zone in zones" :key="zone.id" :value="zone.name">
            {{ zone.name }}
          </option>
        </select>
      </div>

      <!-- VLAN Selection -->
      <div class="vlan-selection">
        <label class="modal-label">VLAN</label>
        <select v-model="selectedVlan" class="modal-input">
          <option value="">Select a VLAN</option>
          <option v-for="vlan in vlans" :key="vlan.id" :value="vlan.vlan">
            {{ vlan.vlan }}
          </option>
        </select>
      </div>


      <!-- Botones -->
      <div class="modal-actions">
        <button
          class="btn btn-primary"
          :disabled="!selectedService"
          @click="confirmAuthorization"
        >
          Aceptar
        </button>
        <button class="btn btn-secondary" @click="closeModal">Cancelar</button>
      </div>
    </div>
  </div>

</template>

<script setup>
import {ref, onMounted, watch} from 'vue';

const props = defineProps({
  oltId: {
    type: [String, Number],
    required: true
  }
});

const showModal = ref(false);
const selectedOnu = ref(null);

const services = ref([]);
const servicesLoading = ref(false);
const search = ref('');
const selectedService = ref(null);
const onuMode = ref('Routing');
const zones = ref([]);
const selectedZone = ref(null);
const vlans = ref([]);
const selectedVlan = ref(null);

const oltCards = ref([]);
const unconfiguredOnus = ref([]);
const loading = ref(true);

function debounce(fn, delay = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

const fetchServices = debounce(async () => {
  const term = search.value.trim();
  if (term.length < 2) {
    services.value = [];
    return;
  }

  servicesLoading.value = true;
  try {
    const {data} = await Nova.request().get('/nova-vendor/smartolt/olt/services', {
      params: {q: term}              // ?q=Palabra
    });
    services.value = data.data || [];  // [{id,name}]
  } catch (e) {
    Nova.error('No se pudieron obtener los servicios');
  } finally {
    servicesLoading.value = false;
  }
}, 350);

watch(search, fetchServices);


const fetchOltCards = async () => {
  try {
    const response = await Nova.request().get(`/nova-vendor/smartolt/olt/${props.oltId}/cards`);
    oltCards.value = response.data.response || [];
  } catch (error) {
    console.error('Error fetching OLT cards:', error);
    Nova.error('Failed to load OLT cards details');
  }
};

const fetchUnconfiguredOnus = async () => {
  try {
    const response = await Nova.request().get(`/nova-vendor/smartolt/olt/${props.oltId}/unconfigured-onus`);
    unconfiguredOnus.value = response.data.response || [];
  } catch (error) {
    console.error('Error fetching unconfigured ONUs:', error);
    Nova.error('Failed to load unconfigured ONUs');
  }
};

const loadData = async () => {
  loading.value = true;
  await Promise.all([fetchOltCards(), fetchUnconfiguredOnus(), fetchZones(), fetchVlans()]);
  loading.value = false;
};

const fetchVlans = async () => {
  try {
    const response = await Nova.request().get(`/nova-vendor/smartolt/olt/vlans/${props.oltId}`);
    vlans.value = response.data.response || [];
  } catch (error) {
    console.error('Error fetching VLANs:', error);
    Nova.error('Failed to load VLANs');
  }
};

const fetchZones = async () => {
  try {
    const response = await Nova.request().get('/nova-vendor/smartolt/olt/zones');
    zones.value = response.data.response || [];
  } catch (error) {
    console.error('Error fetching zones:', error);
    Nova.error('Failed to load zones');
  }
};
const goBack = () => {
  const pathParts = window.location.pathname.split('/');
  pathParts.pop();
  window.location.href = pathParts.join('/');
};


/* ─────────────────────────────
   Autorización
───────────────────────────── */
const authorizeOnu = (onu) => {
  selectedOnu.value = onu;
  services.value = [];
  selectedService.value = null;
  search.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const confirmAuthorization = async () => {
  if (!selectedService.value || !selectedZone.value || !selectedVlan.value) return;

  try {
    await Nova.request().post('/nova-vendor/smartolt/onu/authorize', {
      onu: selectedOnu.value,
      service_id: selectedService.value.id,
      onu_mode: onuMode.value,
      zone: selectedZone.value,
      vlan: selectedVlan.value
    });

    Nova.success('ONU autorizada correctamente');
    closeModal();
    await fetchUnconfiguredOnus();     // refresca la tabla
  } catch (e) {
    Nova.error('Error al autorizar la ONU');
  }
};


onMounted(() => {
  loadData();
});
</script>

<style scoped>
.olt-detail-container {
  padding: 20px;
  font-family: "Segoe UI", sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.title {
  font-size: 24px;
  font-weight: bold;
  margin: 0;
}

.section {
  margin-bottom: 32px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  padding: 16px;
  margin: 0;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 16px;
  padding: 16px;
}

.card {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.card-title {
  display: flex;
  flex-direction: column;
}

.slot-label {
  font-weight: 600;
  font-size: 16px;
}

.card-type {
  font-size: 14px;
  color: #6b7280;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 500;
}

.status-online {
  background-color: #dcfce7;
  color: #166534;
}

.status-offline {
  background-color: #fee2e2;
  color: #991b1b;
}

.card-body {
  padding: 12px;
}

.info-row {
  margin-bottom: 8px;
  font-size: 14px;
}

.table-container {
  padding: 16px;
  overflow-x: auto;
}

.onu-table {
  width: 100%;
  border-collapse: collapse;
}

.onu-table th, .onu-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.onu-table th {
  background-color: #f9fafb;
  font-weight: 600;
}

.empty-state {
  padding: 32px;
  text-align: center;
  color: #6b7280;
}

.btn {
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  font-size: 14px;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
}

.btn-secondary {
  background-color: #6b7280;
  color: white;
}

.loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 48px;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top: 4px solid #3b82f6;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
/* …estilos existentes… */

/* ───────── Modal ───────── */
.onu-mode-selection {
  margin: 16px 0;
}

.radio-group {
  display: flex;
  gap: 20px;
}

.radio-group label {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(0,0,0,.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.modal {
  width: 430px;
  max-width: 90%;
  background: #fff;
  border-radius: 8px;
  padding: 24px;
  box-shadow: 0 10px 25px rgba(0,0,0,.15);
}
.modal-title {
  margin-top: 0;
  margin-bottom: 16px;
  font-size: 20px;
  font-weight: 600;
}
.modal-label {
  font-weight: 500;
  display: block;
  margin-bottom: 6px;
}
.modal-input {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #cbd5e1;
  border-radius: 5px;
  margin-bottom: 12px;
}
.loading-small {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 8px;
}
.services-list {
  max-height: 180px;
  overflow-y: auto;
  margin: 0; padding: 0;
  list-style: none;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
}
.services-list li {
  padding: 8px 10px;
  cursor: pointer;
}
.services-list li:hover,
.services-list li.selected {
  background: #e7f1ff;
}
.modal-actions {
  margin-top: 18px;
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

</style>
