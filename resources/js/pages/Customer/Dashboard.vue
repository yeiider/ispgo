<script setup>
import Layout from "./Layouts/Dashboard.vue";

const props = defineProps({sidebar: Object, customer: Object})

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">My Account</h1>
    <h2 class="text-2xl mt-5 md:mt-10 font-light">Account information</h2>
    <hr class="my-2 border-gray-300">
    <div class="grid grid-cols-2 mt-3 md:mt-7">
      <div class="flex flex-col">
        <h3 class="font-semibold">Contact information</h3>
        <p>Lorem ipsum dolor.</p>
        <p>Lorem ipsum dolor.</p>
        <a href="">edit</a>
      </div>
    </div>
  </Layout>
</template>

<style scoped>

</style>
