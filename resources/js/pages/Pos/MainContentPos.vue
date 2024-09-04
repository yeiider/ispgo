<template>
  <div>
    <div v-if="todayBox">
      <!-- Contenido Principal si existe todayBox -->
      <div class="flex flex-col md:flex-row w-[90%] m-auto">
        <div class="w-full md:w-1/6 p-4 rounded-lg shadow-md">
          <img :src="img" alt="logo"/>
          <div class="p-4">
            <div class="text-white w-full bg-red-400 rounded-full p-10 text-center font-bold  uppercase">
              {{ formatPrice(todayBox.end_amount) }}
              <p class="text-white font-bold  uppercase">{{ box.name }}</p>
            </div>
          </div>

          <CalculatorComponent/>
        </div>
        <div class="w-full md:w-2/3 p-4">
          <div class="header mt-2">
            <SearchBar @searchCustomers="setCustomers"/>
          </div>
          <div class="nav">
            <nav class="bg-background text-foreground p-4">
              <ul class="flex space-x-4">
                <li>
                  <a href="#" @click.prevent="selectMenu('invoices')" class="block px-4 py-2 rounded-md text-foreground hover:text-primary hover:bg-foreground/10 cursor-pointer">Facturas pagadas</a>
                </li>
              </ul>
            </nav>
          </div>
          <main class="detail bg-gray-100 justify-around max-h-dvh flex h-[65vh]">
            <InvoiceComponent v-if="currentComponent==='invoices' && !selectInvoice"  />

            <InvoiceDetails v-if="selectInvoice" @searchCustomers="setCustomers" @selectedInvoice="setSelectInvoice"
                            :invoice="selectInvoice" :todayBox="todayBox" :customer="customerSelected"/>
          </main>
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
import {ref, provide, onMounted} from 'vue';
import SearchBar from './SearchBar.vue';
import CashierInfo from './CashierInfo.vue';
import InvoiceDetails from './InvoiceDetails.vue';
import CustomerList from './CustomerList.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import RealTimeClock from "./RealTimeClock.vue";
import CreateDailyBoxForm from './CreateDailyBoxForm.vue'; // Importa el componente de formulario
import axios from "axios";
import CalculatorComponent from "./CalculactorComponent.vue";
import InvoiceComponent from "./InvoiceListComponent.vue";

const customers = ref([]);
const selectedInvoice = ref(null);
const currentComponent = ref('invoices');

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

onMounted(() => {
  axios.get('/invoice/find-by-box').then((response) => {
    console.log(response)
  })
});

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

const selectMenu = (menuOption) => {
  currentComponent.value = menuOption;
};
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>
