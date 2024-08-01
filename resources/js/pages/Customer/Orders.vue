<script setup>

import Layout from "./Layouts/Dashboard.vue";

defineProps({sidebar: Object})

</script>

<template>
  <Layout :sidebar="sidebar">
  </Layout>
</template>

<style scoped>

</style>
