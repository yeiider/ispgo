<script setup>
import Layout from "./Layouts/Dashboard.vue";
import {Icon} from './../Icons/index.js'
import {__} from "../../translation.js";

defineProps({
  sidebar: Object,
  customer: Object,
  route_edit_customer: String
})

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">{{__('My Account')}}</h1>
    <h2 class="text-2xl mt-5 md:mt-10 font-light">{{ __('Account information') }}</h2>
    <hr class="my-2 border-gray-300">

    <div class="flex-1 overflow-auto p-6">
      <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('First Name') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.first_name }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Last Name') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.last_name }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Date of Birth') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.date_of_birth ? customer.date_of_birth_formatted : '—' }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Phone Number') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.phone_number ? customer.phone_number : '—' }}
          </dd>
        </div>
        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Email Address') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.email_address }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Document Type') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.document_type }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500">{{ __('Identity Document') }}</dt>
          <dd class="text-sm text-gray-900 font-semibold">
            {{ customer.identity_document }}
          </dd>
        </div>

      </dl>
      <div class="mt-6">
        <a :href="route_edit_customer"
           class="btn btn-outline-primary">
          <span>{{ __('Edit') }}</span>
          <Icon.Pencil class="size-5"/>
        </a>
      </div>
    </div>
  </Layout>
</template>

