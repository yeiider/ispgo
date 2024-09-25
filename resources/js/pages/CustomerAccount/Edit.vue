<script setup>
import Layout from "./Layouts/Dashboard.vue";
import Input from "../Components/Input.vue";
import Select from "../Components/Select.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Button from "../Components/Button.vue";
import PaperAirplane from './../Icons/PaperAirplane.vue'

const props = defineProps({
  sidebar: Object,
  customer: {
    type: Object,
    required: true // ensure the prop is required to avoid undefined errors
  },
  documentTypes: Array,
  routeUpdateCustomer: String
});

const DEFAULT_FORM_VALUES = {
  first_name: null,
  last_name: null,
  document_type: null,
  identity_document: null,
  email_address: null,
  phone_number: null,
  date_of_birth: null,
  password: null,
  password_confirmation: null
};

function createFormValues(customer) {
  return {
    ...DEFAULT_FORM_VALUES,
    first_name: customer?.first_name || null,
    last_name: customer?.last_name || null,
    document_type: customer?.document_type || null,
    identity_document: customer?.identity_document || null,
    email_address: customer?.email_address || null,
    phone_number: customer?.phone_number || null,
    date_of_birth: customer?.date_of_birth_formatted || null
  };
}

const form = useForm(createFormValues(props.customer));

const handleSubmit = () => {
  if (props.routeUpdateCustomer) {
    form.put(props.routeUpdateCustomer);
  }
};

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">Edit Account</h1>
    <h2 class="text-2xl mt-5 md:mt-10 font-light">Form</h2>
    <hr class="my-2 border-gray-300">
    <form class="space-y-5 w-full mt-10" @submit.prevent="handleSubmit">
      <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
        <Input label="First Name" v-model="form.first_name" type="text" class="col-span-2" :required="true"/>
        <Input label="Last Name" v-model="form.last_name" type="text" class="col-span-2" :required="true"/>
        <Input label="Email Address" v-model="form.email_address" type="email" class="col-span-3" :required="true"/>
        <Input label="Phone Number" v-model="form.phone_number" type="number" class="col-span-1"/>
        <Input label="Date of Birth" v-model="form.date_of_birth" type="date" class="col-span-1"/>
        <Select
          v-model="form.document_type"
          :model-value="customer?.document_type"
          :options="documentTypes"
          :required="true"
          label="Document type"
        />
        <Input label="Identity Document" id="identity_document" v-model="form.identity_document"
               name="identity_document" type="number" class="col-span-1" :required="true"/>
      </div>
      <hr class="my-10">

      <div>
        <Button
          class=""
          :is-loading="form.processing"
          type="submit">
          <span>Submit</span>
          <PaperAirplane/>
        </Button>
      </div>
    </form>
  </Layout>
</template>
