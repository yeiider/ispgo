<script setup>
import LayoutAuth from "../Layouts/Auth.vue";
import Input from "../../Components/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Button from "../../Components/Button.vue";

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
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email" :error="'error' in props.errors"/>
      <Input v-model="form.password" :required="true" type="password" label="Password" id="password" name="password" :error="'error' in props.errors"/>
      <p v-if="'error' in props.errors" class="text-sm text-red-500">{{errors.error}}*</p>
      <div class="flex justify-between items-center mt-6 text-sm leading-5">
        <a href="/customer/password/reset" class="underline cursor-pointer opacity-[67%] hover:opacity-[80%]">Forget
          your password?</a>
      </div>
      <Button
        :is-loading="form.processing"
        type="submit"
        class="w-full">
        Continue
      </Button>
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

