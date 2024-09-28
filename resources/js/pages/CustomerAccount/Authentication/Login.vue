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
      <div v-if="form.errors?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
           role="alert">
        <span class="block sm:inline">{{ form.errors?.error }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg fill="red" @click="() => form.errors.error = false" width="20px" height="20px" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
            <path
              d="M12,11.2928932 L16.1464466,7.14644661 C16.3417088,6.95118446 16.6582912,6.95118446 16.8535534,7.14644661 C17.0488155,7.34170876 17.0488155,7.65829124 16.8535534,7.85355339 L12.7071068,12 L16.8535534,16.1464466 C17.0488155,16.3417088 17.0488155,16.6582912 16.8535534,16.8535534 C16.6582912,17.0488155 16.3417088,17.0488155 16.1464466,16.8535534 L12,12.7071068 L7.85355339,16.8535534 C7.65829124,17.0488155 7.34170876,17.0488155 7.14644661,16.8535534 C6.95118446,16.6582912 6.95118446,16.3417088 7.14644661,16.1464466 L11.2928932,12 L7.14644661,7.85355339 C6.95118446,7.65829124 6.95118446,7.34170876 7.14644661,7.14644661 C7.34170876,6.95118446 7.65829124,6.95118446 7.85355339,7.14644661 L12,11.2928932 Z"/>
          </svg>
        </span>
      </div>
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email"/>
      <Input v-model="form.password" :required="true" type="password" label="Password" id="password" name="password"/>
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

