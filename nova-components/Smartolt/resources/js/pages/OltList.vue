<template>
  <div class="olt-dashboard">
    <h2 class="title">OLT Management Dashboard</h2>

    <div class="olt-stats">
      <div class="stat">
        <Server class="icon" />
        <div>
          <div class="stat-label">Total OLTs</div>
          <div class="stat-value">{{ olts.length }}</div>
        </div>
      </div>
    </div>

    <div class="olt-grid">
      <div
        v-for="olt in olts"
        :key="olt.id"
        class="olt-card"
      >
        <div class="olt-header">
          <div class="olt-name">
            <Signal class="icon-small" />
            {{ olt.name }}
          </div>
          <div
            :class="['status-indicator', olt.status === 'online' ? 'green' : 'yellow']"
            :title="olt.status"
          ></div>
        </div>

        <div class="olt-body">
          <div class="info-row">
            <MapPin class="icon-small" />
            <span>{{ olt.location || '—' }}</span>
          </div>

          <div class="info-row">
            <Cpu class="icon-small" />
            <span>{{ olt.olt_hardware_version }}</span>
          </div>

          <div class="info-row">
            <Network class="icon-small" />
            <strong>IP:</strong> {{ olt.ip }}
          </div>

          <div class="info-row">
            <Terminal class="icon-small" />
            <strong>Telnet:</strong> {{ olt.telnet_port }}
          </div>

          <div class="info-row">
            <Activity class="icon-small" />
            <strong>SNMP:</strong> {{ olt.snmp_port }}
          </div>
        </div>

        <div class="olt-footer">
          <button class="btn" @click="monitorOlt(olt)">Monitor</button>
          <button class="btn btn-secondary" @click="configureOlt(olt)">Configure</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import {
  Server,
  Signal,
  MapPin,
  Cpu,
  Network,
  Terminal,
  Activity
} from 'lucide-vue-next';

const olts = ref([]);

const fetchOlts = async () => {
  try {
    const response = await Nova.request().get('/nova-vendor/smartolt/olt/list');
    olts.value = response.data.response || [];
  } catch (e) {
    console.error('Error loading OLTs:', e);
  }
};

const monitorOlt = (olt) => {
  window.location.href = `${window.location.pathname}/${olt.id}`;
};

const configureOlt = (olt) => {
  console.log('Configuring OLT', olt.name);
};

onMounted(() => {
  fetchOlts();
});
</script>

<style scoped>
.olt-dashboard {
  padding: 20px;
  font-family: "Segoe UI", sans-serif;
}

.title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 16px;
}

.olt-stats {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
}

.stat {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  padding: 12px;
  border-radius: 8px;
}

.stat-label {
  font-size: 12px;
  color: #6b7280;
}

.stat-value {
  font-size: 18px;
  font-weight: bold;
}

.icon {
  width: 24px;
  height: 24px;
  color: #3b82f6;
}

.olt-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}

.olt-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 16px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.olt-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.olt-name {
  font-weight: 600;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.status-indicator.green {
  background-color: #22c55e;
}

.status-indicator.yellow {
  background-color: #facc15;
}

.olt-body {
  font-size: 14px;
  color: #374151;
  margin-bottom: 16px;
}

.info-row {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 4px 0;
}

.icon-small {
  width: 16px;
  height: 16px;
  color: #4b5563;
}

.olt-footer {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.btn {
  background-color: #3b82f6;
  color: white;
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
}

.btn-secondary {
  background-color: #6b7280;
}
</style>
