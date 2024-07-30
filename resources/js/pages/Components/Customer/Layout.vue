<template>
  <div class="relative w-full h-full">
    <main id="main-content"
          class="flex relative z-30 sm:items-center sm:py-10">
      <div class="container mx-auto">
        <div class="flex">
          <SideMenu class="w-1/5"></SideMenu>
          <div class="p-5 bg-gray-50 w-4/5">
            <slot></slot>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import SideMenu from "./SideMenu.vue";

</script>

<style scoped>

</style>
