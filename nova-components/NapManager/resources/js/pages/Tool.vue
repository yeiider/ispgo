<template>
  <div class="nap-manager-wrapper">
    <Head title="NAP Manager"/>

    <Heading class="nap-mb-6">NAP Manager</Heading>

    <div class="nap-mb-6">
      <TabGroup @change="handleTabChange">
        <TabList class="tab-menu nap-divide-x dark:nap-divide-gray-700 nap-border-l-gray-200 nap-border-r-gray-200 nap-border-t-gray-200 nap-border-b-gray-200 dark:nap-border-l-gray-700 dark:nap-border-r-gray-700 dark:nap-border-t-gray-700 dark:nap-border-b-gray-700">
          <Tab as="template" v-slot="{ selected }">
            <button
              :class="[
                selected
                  ? 'active nap-text-primary-500 nap-font-bold nap-border-b-2 !nap-border-b-primary-500'
                  : 'nap-text-gray-600 hover:nap-text-gray-800 dark:nap-text-gray-400 hover:dark:nap-text-gray-200',
              ]"
              class="tab-item"
            >
              <span class="nap-flex nap-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="nap-h-5 nap-w-5 nap-mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd"/>
                </svg>
                Map View
              </span>
            </button>
          </Tab>
          <Tab as="template" v-slot="{ selected }">
            <button
              :class="[
                selected
                  ? 'active nap-text-primary-500 nap-font-bold nap-border-b-2 !nap-border-b-primary-500'
                  : 'nap-text-gray-600 hover:nap-text-gray-800 dark:nap-text-gray-400 hover:dark:nap-text-gray-200',
              ]"
              class="tab-item"
            >
              <span class="nap-flex nap-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="nap-h-5 nap-w-5 nap-mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path
                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
                </svg>
                Flow View
              </span>
            </button>
          </Tab>
        </TabList>

        <TabPanels>
          <TabPanel>
            <Card class="nap-overflow-hidden nap-p-4" style="min-height: 600px">
              <div class="nap-h-full">
                <NapMapComponent :googleMapsApiKey="GOOGLE_MAPS_API_KEY"/>
              </div>
            </Card>
          </TabPanel>
          <TabPanel>
            <Card class="nap-overflow-hidden nap-p-4" style="min-height: 600px">
              <div class="nap-h-full">
                <NapFlowComponent/>
              </div>
            </Card>
          </TabPanel>
        </TabPanels>
      </TabGroup>
    </div>
  </div>
</template>

<script>
import NapMapComponent from '../components/NapMapComponent.vue'
import NapFlowComponent from '../components/NapFlowComponent.vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

export default {
  components: {
    NapMapComponent,
    NapFlowComponent,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel
  },

  props: {
    GOOGLE_MAPS_API_KEY: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      activeTab: 0
    }
  },

  methods: {
    handleTabChange(index) {
      this.activeTab = index
    }
  },
}
</script>

<style>
.nap-manager-wrapper .h-full {
  height: 100%;
  min-height: 600px;
}
.nap-manager-wrapper .tab-menu {
  margin-bottom: 15px;
  display: flex;
  gap: 10px;

  button {
    outline: none;
  }
}
</style>
