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
  },
  routeResetPassword: {
    type: String,
    required: true
  }
})

const form = useForm({
  email_address: null
})

const submit = () => {
  if (form.email_address) {
    form.post(props.routeResetPassword)
  }
}

</script>

<template>
  <LayoutAuth title="Reset password">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <p class="mt-0 text-center text-gray-700">You are having trouble logging in?</p>
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email"
             :error="'email_address' in props.errors">
        <p class="text-red-500 text-sm mt-2">{{ props.errors.email_address }}</p>
      </Input>
      <Button
        :is-loading="form.processing"
        type="submit"
        class="w-full">
        <span>Send E-mail</span>
        <Icon.PaperAirplane/>
      </Button>
    </form>
  </LayoutAuth>
</template>
