<script setup>
defineProps({
  title: String
})
const logoSrc = '/img/logo2.png'
</script>

<template>
  <div id="auth-login" class="relative w-full h-full">
    <main id="auth-main-content"
          class="flex relative z-30 flex-col justify-center w-screen min-h-screen items-stretch sm:items-center sm:py-10">
      <div class="relative w-full sm:max-w-md sm:mx-auto">
        <div id="auth-container"
             class="flex relative top-0 z-20 flex-col justify-center items-stretch px-10 py-8 w-full h-screen bg-white border-gray-200 sm:top-auto sm:h-full sm:border sm:rounded-xl">
          <div id="auth-heading-container" class="flex flex-col sm:mx-auto sm:w-full mb-5 sm:max-w-md items-center">
            <div class="flex flex-col w-full items-center mb-5">
              <a href="/public" style="height:32px; width:auto; display:block">
                <img :src="logoSrc" alt="logo" width="80"/>
              </a>
              <h1 id="auth-heading-title" class="mt-2 text-xl font-medium leading-9">{{ title }}</h1>
            </div>
            <slot></slot>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

