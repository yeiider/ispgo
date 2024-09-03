<script setup>
import {Inertia} from '@inertiajs/inertia';
import {ref} from 'vue';

defineProps({
  status: String
})

const resendVerification = () => {
  Inertia.post(route('verification.send'));
};

</script>


<template>
  <div class="verify-email-container">
    <h1 class="text-lg font-bold">Verify Your Email Address</h1>
    <p class="mt-4">
      Before proceeding, please check your email for a verification link.
    </p>
    <p class="mt-4">
      If you did not receive the email,
      <button @click="resendVerification" class="text-blue-500 underline">
        click here to request another
      </button>
      .
    </p>

    <div v-if="status" class="mt-4 text-green-500">
      {{ status }}
    </div>
  </div>
</template>


<style scoped>
.verify-email-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}
</style>
