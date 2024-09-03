<template>
  <div class="flex flex-col md:flex-row w-[90%] m-auto">
    <div class="w-full md:w-1/6 p-4">
      <img :src="img" alt="logo" />
    </div>
    <div class="w-full md:w-2/3 p-4">
      <div class="header mt-2">
        <SearchBar @searchCustomers="setCustomers"/>
      </div>
      <div class="detail bg-gray-100 max-h-dvh flex h-[65vh]">
        <InvoiceDetails v-if="selectInvoice" :invoice="selectInvoice"/>
      </div>
    </div>
    <div class="w-full md:w-1/3 p-4">
      <CashierInfo :cashierName="cashierName"/>
      <RealTimeClock/>
      <SearchBar @searchCustomers="setCustomers"/>

      <CustomerList :customers="customers" @invoiceSelected="setSelectInvoice"/>
    </div>
  </div>
</template>

<script setup>
import {ref,provide} from 'vue';
import SearchBar from './SearchBar.vue';
import CashierInfo from './CashierInfo.vue';
import InvoiceDetails from './InvoiceDetails.vue';
import CustomerList from './CustomerList.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import RealTimeClock from "./RealTimeClock.vue";

const customers = ref([]);
const selectedInvoice = ref(null);

const img = "/img/logo.svg";
const {props} = usePage();
const cashier = props.value.cashier;
const config = props.value.config;

const cashierName = ref(cashier);
const selectInvoice = ref(null);

const setCustomers = (customerData) => {
  customers.value = customerData;
  selectedInvoice.value = null;
}

const formatPrice = (price) => {
  if (config.currencySymbol) {
    return `${config.currencySymbol}${parseInt(price)}`;
  } else {
    return `${config.currency} ${parseInt(price)}`;

  }
};

// Proveer la configuración y la función formatPrice a los componentes hijos
provide('config', config);
provide('formatPrice', formatPrice);

const setSelectInvoice = (invoice) => {
  selectInvoice.value = invoice;
}
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>
