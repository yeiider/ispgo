<script setup>

import Layout from "./Components/Customer/Layout.vue";

const props = defineProps({sidebar: Object})

</script>

<template>
  <Layout :sidebar="sidebar">
  </Layout>
</template>

<style scoped>

</style>
