<script setup>

import LayoutAuth from "../Layouts/Auth.vue";
import Input from "../../Components/Input.vue";
import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps({
  errors: {
    type: Object,
    required: false
  }
})

const form = useForm({
  email_address: null
})

const submit = () => {

}

</script>

<template>
  <LayoutAuth title="Reset password">
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <p class="mt-4 text-center text-gray-700">You are having trouble logging in?</p>
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email"
             :error="'error' in props.errors"/>
    </form>
  </LayoutAuth>
</template>
