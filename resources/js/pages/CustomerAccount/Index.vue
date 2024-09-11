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

          </dd>
        </div>

      </dl>
      <div class="mt-6">
        <a :href="route_edit_customer"
           class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Edit</a>
      </div>
    </div>
  </Layout>
</template>

