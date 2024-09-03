<script setup>
import Layout from "../Layouts/Dashboard.vue";

const props = defineProps({sidebar: Object, customer: Object, tickets: Object})
</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">Create ticket</h1>
  </Layout>

</template>

<style scoped>

</style>
