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
  },
  flash: {
    type: Object,
    required: false
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
    <p class="mt-0 text-center text-gray-700 mb-4">You are having trouble logging in?</p>
    <form class="space-y-5 w-full" @submit.prevent="submit">
      <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3" role="alert" v-if="props.flash?.status">
        <div class="flex items-center gap-1">
          <div class="py-1">
            <Icon.Megaphone/>
          </div>
          <div>
            <p class="text-sm">{{ props.flash.status }}</p>
          </div>
        </div>
      </div>
      <Input v-model="form.email_address" :required="true" type="email" label="Email Address" id="email" name="email"
             :error="'email_address' in props.errors">
        <p v-if="errors && 'email_address' in errors" class="text-red-500 text-sm mt-2">
          {{ props.errors.email_address }}
        </p>

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
