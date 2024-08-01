<script setup>

import Layout from "./Layouts/Dashboard.vue";

defineProps({sidebar: Object, customer: Object})

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">My orders</h1>
  </Layout>
</template>

<style scoped>

</style>
