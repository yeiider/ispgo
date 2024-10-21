<script setup>
import LayoutAuth from "../Layouts/Auth.vue";
import Input from "../../Components/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Button from "../../Components/Button.vue";
import {Icon} from "../../Icons/index.js";

const props = defineProps({
  errors: {
    type: Object,
    required: false
  }
})

const form = useForm({
  email_address: null,
  password: null,
});

const submit = () => {
  form.post('login');
}
</script>

<template>
  <LayoutAuth title="Sign in">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <Input :focus="true" v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email" :error="errors && 'error' in errors" />
      <Input v-model="form.password" :required="true" type="password" label="Password" id="password" name="password" :error="errors && 'error' in errors">
        <p v-if="errors" class="text-red-500 mt-2">{{errors.error}}</p>
      </Input>
      <div class="flex justify-between items-center mt-6 text-sm leading-5">
        <a href="/customer/password/reset" class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]">Forget
          your password?</a>
      </div>
      <Button
        :is-loading="form.processing"
        type="submit"
        class="w-full">
        <span>Continue</span>
        <Icon.LockOpen/>
      </Button>
    </form>
    <div class="mt-3 space-x-0.5 text-sm leading-5 text-left w-full" style="color:#212936">
      <span class="opacity-[47%]"> Don't have an account? </span>
      <a class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]" data-auth="register-link"
         href="/customer/register">
        <span>Sign up</span>
      </a>
    </div>
  </LayoutAuth>
</template>

