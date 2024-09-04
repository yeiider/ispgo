<template>
  <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h2 class="text-xl font-bold mb-4">Create Daily Box for Today</h2>
      <form @submit.prevent="createDailyBox">
        <div class="mb-4">
          <label for="start_amount" class="block text-gray-700">Start Amount:</label>
          <input type="number" v-model="startAmount" required class="w-full p-2 border rounded-lg"/>
        </div>
        <div class="flex justify-end">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Daily Box
          </button>
        </div>
        <p v-if="error" class="text-red-500 mt-4">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue';
import {usePage} from "@inertiajs/inertia-vue3";
import axios from "axios";

const emit = defineEmits(['dailyBoxCreated']);


const {props} = usePage();
const boxId = props.value.config.box.id;

const startAmount = ref(0);
const error = ref(null);

const createDailyBox = () => {
  axios.post('/admin/daily-boxes/create', {
    box_id: boxId,
    start_amount: startAmount.value,
  }).then(response => {
    if (response.status===201) {
      emit('dailyBoxCreated',response.data.dailyBox);
    } else {
      error.value = 'There was an error creating the Daily Box.';
    }
  }).catch(err => {
    error.value = err.message;
  });
}
</script>

<style scoped>
</style>
