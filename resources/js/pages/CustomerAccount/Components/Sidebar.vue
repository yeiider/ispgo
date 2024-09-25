<script setup>
import {onMounted, ref} from "vue";
import {Icon} from "../../Icons/index.js";


const props = defineProps({sidebar: Object, customer: Object});

const href = window.location.href;

const fullName = ref('')
onMounted(() => {
  let customer = props.customer;
  fullName.value = `${customer?.first_name} ${customer?.last_name}`;
})

const isActive = ref(false);
const logoSrc = '/img/logo2.png'

const showMenu = () => {
  isActive.value = !isActive.value;
}
</script>

<template>
  <aside v-if="sidebar" class="w-auto border-e bg-white sm:w-1/2 md:w-1/3 lg:w-1/5">
    <button class="block sm:hidden top-4 p-4 ml-auto" @click="showMenu()">
      <svg v-if="isActive" width="20px" height="20px" viewBox="0 0 24 24" fill="none"
           xmlns="http://www.w3.org/2000/svg">
        <path
          d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
          fill="#0D0D0D"/>
      </svg>

      <svg v-if="!isActive" fill="#000000" width="20px" height="20px" viewBox="0 0 30 30"
           xmlns="http://www.w3.org/2000/svg">
        <path
          d="M.5 24.006h29c.277 0 .5.223.5.5s-.223.5-.5.5H.5c-.277 0-.5-.223-.5-.5s.223-.5.5-.5zm0-10.003h29c.277 0 .5.223.5.5s-.223.5-.5.5H.5c-.277 0-.5-.223-.5-.5s.223-.5.5-.5zM.5 4h29c.277 0 .5.223.5.5s-.223.5-.5.5H.5C.223 5 0 4.777 0 4.5S.223 4 .5 4z"/>
      </svg>
    </button>
    <div
      class="h-screen flex-col justify-between  hidden sm:flex"
      :class="{active: isActive}">
      <div class="px-4 py-6">
        <div class="flex justify-center">
          <a href="/" style="">
          <span style="height:32px; width:auto;" class="flex justify-center mb-2">
           <img :src="logoSrc" alt="logo sass">
          </span>
            <span>
            {{ sidebar.app_name }}
          </span>
          </a>
        </div>

        <ul class="mt-6 space-y-1">
          <li v-for="(link, key) in sidebar.links" :key="key">
            <a
              :href="link.url"
              class="block rounded-md px-4 py-3 text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple"
              :class="link.url === href ? 'text-[#0ea5e9] bg-purple-50 font-bold border-l-4 border-purple': ''"
            >
              {{ link.title }}
            </a>
          </li>
        </ul>
      </div>
      <div
        class="sticky inset-x-0 bottom-0 border-t border-gray-100 hidden sm:block"
        :class="{active: isActive}">
        <div class="flex flex-col justify-between flex-wrap gap-2 bg-white p-4">
          <p class="text-sm mb-2 font-semibold">
            <span>{{ fullName }}</span>
            <span class="hidden">{{ customer.email_address }}</span>
          </p>
          <a :href="sidebar.url_logout"
             class="btn btn-outline-danger">
            <span>Logout</span>
            <Icon.ArrowLongRight/>
          </a>
        </div>
      </div>
    </div>
  </aside>
</template>

<style scoped>
aside .active {
  display: flex;
}

aside.active.sticky {
  display: block;
}
</style>
