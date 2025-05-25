<template>
  <div>
    <Head title="Smartolt"/>

    <Board :resourceId="resourceId"></Board>
  </div>
</template>

<script>
import Board from '../components/OnuManager'

export default {
  props: {
    resourceId: {
      type: String,
      required: false
    },
    view: {
      type: String,
      default: 'onu'
    }
  },
  components: {
    Board,
  },

  mounted() {
    console.log('Component mounted.')
    console.log('View:', this.view)
    console.log('Resource ID:', this.resourceId)
  },
}
</script>

<style>
/* Scoped Styles */
</style>
