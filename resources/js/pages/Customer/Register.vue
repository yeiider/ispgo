<script setup>
import LayoutAuth from "./Layouts/Auth.vue";
import Input from "./../Components/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Select from "../Components/Select.vue";
import Button from "./../Components/Button.vue"

const form = useForm({
  first_name: null,
  last_name: null,
  document_type: null,
  identity_document: null,
  email_address: null,
  password: null,
  password_confirmation: null,
});

const submit = () => {
  form.post('register');
}

defineProps({
  documentTypes: Array
})

</script>

<template>
  <LayoutAuth title="Sign up">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <div v-if="form.errors.error">{{ form.errors.email }}</div>
      <Input
        v-model="form.first_name"
        type="text"
        label="Firs name"
        :required="true"
        id="first_name"
        name="first_name"
      />

      <Input
        v-model="form.last_name"
        type="text"
        label="Last name"
        :required="true"
        id="last_name"
        name="last_name"
      />

      <Select
        v-model="form.document_type"
        :options="documentTypes"
        :required="true"
        label="Document type"
      />

      <Input
        v-model="form.identity_document"
        type="number"
        :required="true"
        label="Identity document"
        id="identity_document"
        name="identity_document"
      />

      <Input
        v-model="form.email_address"
        type="email"
        :required="true"
        label="Email Address"
        id="email"
        name="email"
      />

      <Input
        v-model="form.password"
        type="password"
        :required="true"
        label="Password"
        id="password"
        name="password"
      />

      <Input
        v-model="form.password_confirmation"
        type="password"
        :required="true"
        label="Confirm Password"
        id="password_confirmation"
        name="password_confirmation"
      />

      <Button
        :is-loading="form.processing"
        type="submit">
        Continue
      </Button>
    </form>
    <div class="mt-3 space-x-0.5 text-sm leading-5 text-left w-full" style="color:#212936">
      <span class="opacity-[47%]">Already have an account? </span>
      <a class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]" data-auth="register-link"
         href="/customer/login">
        Sign up
      </a>
    </div>
  </LayoutAuth>
</template>

<style scoped>

</style>
