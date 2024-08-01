<script setup>
import LayoutAuth from "./Layouts/Auth.vue";
import Input from "./../Components/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";

const form = useForm({
  email_address: null,
  password: null
});

const submit = () => {
  form.post('login');
}


</script>

<template>
  <LayoutAuth title="Sign in">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <div v-if="form.errors.error">{{ form.errors.email }}</div>
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email"/>
      <Input v-model="form.password" :required="true" type="password" label="Password" id="password" name="password"/>
      <div class="flex justify-between items-center mt-6 text-sm leading-5">
        <a href="/customer/password/reset" class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]">Forget
          your password?</a>
      </div>
      <button type="submit"
              class="auth-component-button px-4 py-2.5 text-sm font-medium rounded-md opacity-[95%] hover:opacity-100 focus:ring-2 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center disabled:opacity-50 font-semibold focus:outline-none"
              style=" color:#ffffff; background-color:#0ea5e9;"
              :disabled="form.processing"
      >
        Continue
      </button>
    </form>
    <div class="mt-3 space-x-0.5 text-sm leading-5 text-left w-full" style="color:#212936">
      <span class="opacity-[47%]"> Don't have an account? </span>
      <a class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]" data-auth="register-link"
         href="/customer/register">
        Sign up
      </a>
    </div>
  </LayoutAuth>
</template>

<style scoped>

</style>
