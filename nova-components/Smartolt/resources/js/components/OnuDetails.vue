<template>
  <div class="px-6 py-4">
    <h3 class="text-xl font-bold mb-4">ONU Details</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <p><span class="font-bold">Serial Number:</span> {{ onuDetails.sn }}</p>
        <p><span class="font-bold">Status:</span>
          <span :class="onuDetails.status === 'online' ? 'text-green-500' : 'text-red-500'">
            {{ onuDetails.status }}
          </span>
        </p>
        <p><span class="font-bold">Type:</span> {{ onuDetails.type }}</p>
        <p v-if="onuFullStatus?.details?.['Vendor ID']"><span class="font-bold">Vendor ID:</span> {{ onuFullStatus.details['Vendor ID'] }}</p>
        <p v-if="onuFullStatus?.details?.['HW Version']"><span class="font-bold">HW Version:</span> {{ onuFullStatus.details['HW Version'] }}</p>
        <p v-if="onuFullStatus?.details?.['Detected ONU type']"><span class="font-bold">Detected ONU type:</span> {{ onuFullStatus.details['Detected ONU type'] }}</p>
      </div>
      <div>
        <p><span class="font-bold">Board:</span> {{ onuDetails.board }}</p>
        <p v-if="onuFullStatus?.details?.['Admin state']"><span class="font-bold">Admin State:</span> {{ onuFullStatus.details['Admin state'] }}</p>
        <p v-if="onuFullStatus?.details?.['Phase state']"><span class="font-bold">Phase State:</span> {{ onuFullStatus.details['Phase state'] }}</p>
        <p v-if="onuFullStatus?.details?.['ONU Distance']"><span class="font-bold">Distance:</span> {{ onuFullStatus.details['ONU Distance'] }}</p>
        <p v-if="onuFullStatus?.details?.['Online Duration']"><span class="font-bold">Online Duration:</span> {{ onuFullStatus.details['Online Duration'] }}</p>
        <p v-if="onuFullStatus?.details?.['Description']"><span class="font-bold">Description:</span> {{ onuFullStatus.details['Description'] }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    onuDetails: {
      type: Object,
      required: true
    },
    onuFullStatus: {
      type: Object,
      required: true
    }
  }
}
</script>
