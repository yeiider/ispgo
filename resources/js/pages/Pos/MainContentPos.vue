<template>
  <div>
    <div v-if="todayBox">
      <!-- Contenido Principal si existe todayBox -->
      <div class="flex flex-col md:flex-row w-[90%] m-auto">
        <div class="w-full md:w-1/6 p-4">
          <img :src="img" alt="logo"/>
          <div class="p-4">
            <div class="text-white w-full bg-red-400 rounded-full p-10 text-center font-bold  uppercase">
              {{ formatPrice(todayBox.end_amount) }}
              <p class="text-white font-bold  uppercase">{{ box.name }}</p>
            </div>

          </div>

        </div>
        <div class="w-full md:w-2/3 p-4">
          <div class="header mt-2">
            <SearchBar @searchCustomers="setCustomers"/>
          </div>
          <div class="detail bg-gray-100 max-h-dvh flex h-[65vh]">
            <InvoiceDetails v-if="selectInvoice" @searchCustomers="setCustomers" @selectedInvoice="setSelectInvoice"
                            :invoice="selectInvoice" :todayBox="todayBox" :customer="customerSelected"/>
          </div>
        </div>
        <div class="w-full md:w-1/3 p-4">
          <CashierInfo :cashierName="cashierName"/>
          <RealTimeClock/>
          <SearchBar @searchCustomers="setCustomers"/>
          <CustomerList :customers="customers" @invoiceSelected="setSelectInvoice"
                        @customerSelected="setCustomerSelected"/>
        </div>
      </div>
    </div>
    <div v-else>
      <!-- Mostrar formulario de creación si no existe todayBox -->
      <CreateDailyBoxForm @dailyBoxCreated="setTodayBox"/>
    </div>
  </div>
</template>

<script setup>
import {ref, provide} from 'vue';
import SearchBar from './SearchBar.vue';
import CashierInfo from './CashierInfo.vue';
import InvoiceDetails from './InvoiceDetails.vue';
import CustomerList from './CustomerList.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import RealTimeClock from "./RealTimeClock.vue";
import CreateDailyBoxForm from './CreateDailyBoxForm.vue'; // Importa el componente de formulario

const customers = ref([]);
const selectedInvoice = ref(null);

const img = "/img/logo.svg";
const {props} = usePage();
const cashier = props.value.cashier;
const config = props.value.config;

const cashierName = ref(cashier);
const selectInvoice = ref(null);
const customerSelected = ref(null);

const todayBox = ref(config.todayBox);
const box = ref(config.box);

const setCustomers = (customerData) => {
  customers.value = customerData;
  selectedInvoice.value = null;
}

const formatPrice = (price) => {
  // Asegurarse de que price sea un número con decimales
  const formattedPrice = parseFloat(price).toLocaleString('es-CO', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });

  if (config.currencySymbol) {
    return `${config.currencySymbol}${formattedPrice}`;
  } else {
    return `${config.currency} ${formattedPrice}`;
  }
};


provide('config', config);
provide('formatPrice', formatPrice);

const setSelectInvoice = (invoice) => {
  selectInvoice.value = invoice;
}
const setCustomerSelected = (customer) => {
  customerSelected.value = customer;
}

const setTodayBox = (newBox) => {
  todayBox.value = newBox;
}
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>
