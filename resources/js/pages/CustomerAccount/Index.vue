<script setup>
import Layout from "./Layouts/Dashboard.vue";

defineProps({sidebar: Object, customer: Object, route_edit_customer: String})


</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">My Account</h1>
    <h2 class="text-2xl mt-5 md:mt-10 font-light">Account information</h2>
    <hr class="my-2 border-gray-300">
    <div class="grid grid-cols-2 mt-3 md:mt-7">
      <div class="flex flex-col">
        <h3 class="font-semibold">Details</h3>
        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>First Name:</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.first_name }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Last Name:</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.last_name }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Date of Birth:</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.date_of_birth ? customer.date_of_birth_formatted : '—' }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Phone Number</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.phone_number ? customer.phone_number : '—' }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Email Address</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.email_address }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Document Type</span>
            </h4>

          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.document_type }}
            </p>
          </div>
        </div>

        <div
          class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row  @md/modal:flex-row md:py-0  md:space-y-0">
          <div class="md:w-1/4  md:py-3 ">
            <h4 class="font-normal ">
              <span>Identity Document</span>
            </h4>
          </div>
          <div class="break-all md:w-3/4  @md/modal:w-3/4 md:py-3 lg:break-words ">
            <p class="flex items-center">
              {{ customer.identity_document }}
            </p>
          </div>
        </div>
        <a  :href="route_edit_customer" class="underline">edit</a>
      </div>
    </div>
  </Layout>
</template>

<style scoped>

</style>
