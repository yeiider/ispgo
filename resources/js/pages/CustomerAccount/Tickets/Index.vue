<script setup>

import Layout from "../Layouts/Dashboard.vue";
import {onMounted, ref} from "vue";
import Pagination from "../../Components/Pagination.vue"
import {Icon} from "../../Icons/index.js";

const props = defineProps({
  sidebar: Object,
  customer: Object,
  tickets: Object,
  ticketCreateUrl: {
    type: String,
    required: true
  }
})
const _pagination = ref({});
onMounted(() => {
  const _customer = props.customer;
  const {data, ...rest} = _customer;
  _pagination.value = rest;
});
</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <div class="flex flex-wrap md:flex-nowrap justify-between">
      <h1 class="text-3xl font-semibold text-slate-950">Tickets</h1>
      <div class="actions hidden">
        <a
          :href="ticketCreateUrl"
          class="btn btn-outline-primary mt-4">
          <span>Create tickets</span>
          <Icon.SquaresPlus/>
        </a>
      </div>
    </div>
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
          <div class="overflow-hidden">
            <table
              class="min-w-full text-left text-sm font-light text-surface dark:text-white">
              <thead
                class="border-b border-neutral-200 bg-white font-medium dark:border-white/10 dark:bg-body-dark">
              <tr>
                <th scope="col" class="px-6 py-4">#</th>
                <th scope="col" class="px-6 py-4">Title</th>
                <th scope="col" class="px-6 py-4">Description</th>
                <th scope="col" class="px-6 py-4">Resolution notes</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="item in tickets.data" :key="item.id"
                  class="border-b border-neutral-200 bg-black/[0.02] dark:border-white/10">
                <td class="whitespace-nowrap px-6 py-4 font-medium">
                  {{ item.id }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 font-medium">
                  {{ item.title }}}
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  {{ item.description }}
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  {{ item.resolution_notes }}
                </td>
              </tr>
              </tbody>
            </table>
            <Pagination :pagination="_pagination"/>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
