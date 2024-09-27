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
  routeUpdateCustomer: String,
  routeChangePassword: String,
  errors: {
    type: Object,
    required: false
  }
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
    date_of_birth: customer?.date_of_birth_formatted || null,

  };
}

const form = useForm(createFormValues(props.customer));

const handleSubmit = () => {
  if (props.routeUpdateCustomer) {
    form.put(props.routeUpdateCustomer);
  }
};

const formChangePassword = useForm({
  password: null,
  password_confirmation: null,
  current_password: null
})
const handleChangePassword = () => {
  if (props.routeChangePassword) {
    formChangePassword.put(props.routeChangePassword);
  }
}

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">Edit Account</h1>

    <form class="space-y-5 lg:max-w-[700px] mt-10" @submit.prevent="handleSubmit">
      <h2 class="text-2xl mt-5 md:mt-10 font-light">Edit your account</h2>
      <hr class="my-2 border-gray-300">
      <div class="grid md:grid-cols-4 gap-6">
        <Input
          label="First Name"
          v-model="form.first_name"
          type="text"
          class="md:col-span-2 lg:col-span-2"
          :required="true"
        />

        <Input
          label="Last Name"
          v-model="form.last_name"
          type="text"
          class="md:col-span-2 lg:col-span-2"
          :required="true"
        />

        <Input
          label="Email Address"
          v-model="form.email_address"
          type="email"
          class="md:col-span-4 lg:col-span-3"
          :required="true"
        />

        <Input
          label="Date of Birth"
          v-model="form.date_of_birth"
          type="date"
          class="md:col-span-2 lg:col-span-1"
        />

        <Input
          label="Phone Number"
          v-model="form.phone_number"
          type="number"
          class="md:col-span-2 lg:col-span-2"
        />

        <Select
          v-model="form.document_type"
          :model-value="customer?.document_type"
          :options="documentTypes"
          :required="true"
          label="Document type"
          class="md:col-span-2 lg:col-span-2"
        />
        <Input
          label="Identity Document"
          id="identity_document"
          v-model="form.identity_document"
          name="identity_document"
          type="number"
          class="md:col-span-2 lg:col-span-2"
          :required="true"
        />
      </div>


      <div class="flex justify-end">
        <Button
          :is-loading="form.processing"
          type="submit">
          <span>Submit</span>
          <PaperAirplane/>
        </Button>
      </div>
    </form>

    <form class="space-y-5 lg:max-w-[700px] mt-10" @submit.prevent="handleChangePassword">
      <h2 class="text-2xl mt-5 md:mt-10 font-light">Change your password</h2>
      <hr class="my-2 border-gray-300">

      <Input
        label="New password"
        v-model="formChangePassword.password"
        type="password"
        class="md:col-span-2 lg:col-span-2"
        :required="true"
        :error="'password' in props.errors"
      />

      <Input
        label="Confirm password"
        v-model="formChangePassword.password_confirmation"
        type="password"
        class="md:col-span-2 lg:col-span-2"
        :required="true"
        :error="'password' in props.errors"
      />
      <p class="mt-2 text-sm text-red-500" v-if="props.errors.password">{{ props.errors.password }}*</p>

      <Input
        label="Current password"
        v-model="formChangePassword.current_password"
        type="password"
        class="md:col-span-2 lg:col-span-2"
        :required="true"
        :error="'current_password' in props.errors"
      />
      <p class="mt-2 text-sm text-red-500" v-if="props.errors.current_password">{{ props.errors.current_password }}*</p>

      <div class="flex justify-end">
        <Button
          :is-loading="form.processing"
          type="submit">
          <span>Change password</span>
          <PaperAirplane/>
        </Button>
      </div>
    </form>

  </Layout>
</template>
