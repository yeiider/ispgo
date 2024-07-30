<script setup>
import Layout from "./Components/Customer/Layout.vue";

defineProps({ sidebar: Object })

</script>

<template>
  <Layout>
    {{JSON.stringify(sidebar)}}
  </Layout>
</template>


<style scoped>

</style>
