<template>
  <div class="py-4">
    <div v-if="loading" class="flex justify-center items-center p-4">
      <Loader class="text-60"/>
    </div>
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
      <p>{{ error }}</p>
    </div>
    <div v-else-if="!onuFullStatus?.history || Object.keys(onuFullStatus.history).length === 0" class="p-4">
      <p class="text-center">No history information available for this ONU.</p>
    </div>
    <div v-else>
      <h4 class="text-lg font-bold mb-2">Connection History</h4>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auth Time</th>
              <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offline Time</th>
              <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cause</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(entry, index) in sortedHistory" :key="index" :class="entry.isOnline ? 'bg-green-50' : ''">
              <td class="py-2 px-4 border-b border-gray-200">{{ index }}</td>
              <td class="py-2 px-4 border-b border-gray-200">{{ entry['Auth at'] }}</td>
              <td class="py-2 px-4 border-b border-gray-200">{{ entry['Offline at'] || 'Currently Online' }}</td>
              <td class="py-2 px-4 border-b border-gray-200" :class="getCauseClass(entry.Cause)">{{ entry.Cause }}</td>
            </tr>
          </tbody>
        </table>
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
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: null
    }
  },
  computed: {
    sortedHistory() {
      if (!this.onuFullStatus?.history) return [];

      // Convert the object to an array and sort by index (which is already in the correct order)
      return Object.entries(this.onuFullStatus.history)
        .map(([key, value]) => {
          // Add isOnline flag for styling and keep the original key
          const isOnline = value.Cause && value.Cause.includes('currently online');
          return { ...value, isOnline, key };
        })
        .sort((a, b) => {
          // Sort by most recent first (assuming the keys are numeric and sequential)
          return parseInt(a.key) - parseInt(b.key);
        });
    }
  },
  methods: {
    getCauseClass(cause) {
      if (!cause) return '';

      if (cause.includes('currently online')) {
        return 'text-green-600 font-semibold';
      } else if (cause.includes('Power Fail')) {
        return 'text-yellow-600';
      } else if (cause.includes('Signal Loss')) {
        return 'text-red-600';
      } else {
        return 'text-gray-600';
      }
    }
  }
}
</script>
