<script setup>

import LayoutAuth from "../Layouts/Auth.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "../../Components/Input.vue";
import Button from "../../Components/Button.vue";
import {Icon} from "../../Icons/index.js";

const props = defineProps({
  errors: {
    type: Object,
    required: false
  },
  customer: {
    type: Object,
    required: true
  },
  routeCreatePassword: {
    type: String,
    required: true
  }
})

const form = useForm({
  password: null,
  password_confirmation: null,
  email_address: props.customer.email_address
})
const submit = () => {
  form.post(props.routeCreatePassword)
}
</script>

<template>
  <LayoutAuth title="Create a new password">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <Input
        v-model="form.password"
        type="password"
        :required="true"
        label="Password"
        id="password"
        name="password"
        :error="'password' in errors"
      />

      <Input
        v-model="form.password_confirmation"
        type="password"
        :required="true"
        label="Confirm Password"
        id="password_confirmation"
        name="password_confirmation"
        :error="'password' in errors"
      >
        <p v-if="errors && 'password' in errors" class="text-sm text-red-500 mt-2">{{ errors.password }}*</p>
      </Input>
      <Button
        :is-loading="form.processing"
        type="submit"
        class="w-full">
        <span>Change password</span>
        <Icon.PaperAirplane/>
      </Button>
    </form>
  </LayoutAuth>
</template>

<style scoped>

</style>
