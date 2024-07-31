<script setup>
import LayoutAuth from "./Components/Customer/Auth/Layout.vue";
import Input from "./Components/Customer/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {router} from '@inertiajs/vue3'


const form = useForm({
  email_address: null,
  password: null
});

const submit = () => {
  form.post('login');
  console.log(form)
}


</script>

<template>
  <LayoutAuth title="Sign in">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
        {{ form.progress.percentage }}%
      </progress>
      <div v-if="form.errors.error">{{ form.errors.email }}</div>
      <Input v-model="form.email_address" type="email" label="Email Address" id="email" name="email"/>
      <Input v-model="form.password" type="password" label="Password" id="password" name="password"/>
      <button type="submit"
              class="auth-component-button px-4 py-2.5 text-sm font-medium rounded-md opacity-[95%] hover:opacity-100 focus:ring-2 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center disabled:opacity-50 font-semibold focus:outline-none"
              style=" color:#ffffff; background-color:#0ea5e9;"
      >
        Continue
      </button>
    </form>
  </LayoutAuth>
</template>

<style scoped>

</style>
