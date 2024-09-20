<script setup>
import Layout from "./Layouts/Dashboard.vue";

defineProps({sidebar: Object, customer: Object, route_edit_customer: String})


</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">My Account</h1>
    <h2 class="text-2xl mt-5 md:mt-10 font-light">Account information</h2>
    <hr class="my-2 border-gray-300">

    <div class="flex-1 overflow-auto p-6">


      <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500">First Name:</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.first_name }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Last Name:</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.last_name }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Date of Birth:</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.date_of_birth ? customer.date_of_birth_formatted : '—' }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.phone_number ? customer.phone_number : '—' }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">Email Address</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.email_address }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500">Document Type</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.document_type }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500">Identity Document</dt>
          <dd class="text-sm text-gray-900">
            {{ customer.identity_document }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500"></dt>
          <dd class="text-sm text-gray-900">
            <button class="bg-purple-600 text-white">button</button>
          </dd>
          <div class="relative hidden">
            <input type="text" id="floating_outlined" value="juan jose"
                   class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" "/>
            <label for="floating_outlined"
                   class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Floating
              outlined</label>
          </div>
        </div>

      </dl>
      <div class="mt-6">
        <a :href="route_edit_customer"
           class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Edit</a>
      </div>
    </div>
  </Layout>
</template>

