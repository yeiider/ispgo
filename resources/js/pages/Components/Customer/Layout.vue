<script setup>
import Sidebar from "./Sidebar.vue";
defineProps({ sidebar: Object })
</script>

<template>
  <div class="relative w-full h-full">
    <main id="main-content"
          class="flex relative z-30 sm:items-center sm:py-10">
      <div class="container mx-auto">
        <div class="flex">
          <Sidebar :sidebar="sidebar" class="w-1/5"></Sidebar>
          <div class="p-5 bg-gray-50 w-4/5">
            <slot></slot>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>

</style>
