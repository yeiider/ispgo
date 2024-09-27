<script setup>
import Layout from "../Layouts/Dashboard.vue";
import Input from "../../Components/Input.vue";
import Textarea from "../../Components/Textarea.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import PaperAirplane from "../../Icons/PaperAirplane.vue";
import Button from "../../Components/Button.vue";
import Select from "../../Components/Select.vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";

const props = defineProps({
  sidebar: Object,
  customer: Object,
  issueTypes: {
    type: Array,
    required: true
  }
})

const form = useForm({
  title: null,
  issue_type: null,
  description: null,
  resolution_notes: null,
  contact_method: null,

})
const handleSubmit = () => {

}

const FilePond = vueFilePond();

</script>

<template>
  <Layout :sidebar="sidebar" :customer="customer">
    <h1 class="text-3xl font-semibold text-slate-950">Create ticket</h1>
    <form class="space-y-5 lg:max-w-[700px] mt-10" @submit.prevent="handleSubmit">
      <h2 class="text-2xl mt-5 md:mt-10 font-light">Create a new ticket</h2>
      <hr>
      <hr class="my-2 border-gray-300">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Input
          label="Title"
          v-model="form.title"
          type="text"
          class="col-span-4 md:col-span-2 lg:col-span-3"
          :required="true"
        />
        <Select
          label="Issue Type"
          name="issue_type"
          v-model="form.issue_type"
          :required="true"
          class="col-span-4 md:col-span-2 lg:col-span-2"
          :options="issueTypes"
        />
        <Textarea
          label="Description"
          class="col-span-4"
          v-model="form.description"
          rows="5"
          name="description"
          :required="true"
        ></Textarea>
        <Textarea
          label="Resolution Notes"
          v-model="form.resolution_notes"
          class="col-span-4"
          rows="5"
          name="description"
        ></Textarea>
        <Input
          label="Contact Method"
          v-model="form.contact_method"
          type="text"
          class="col-span-4 md:col-span-2 lg:col-span-2"
          :required="true"
        />
      </div>
      <div class="actions">
        <button
          type="submit"
          class="btn btn-primary w-full md:w-auto"
          :is-loading="form.processing"
        >
          <span>Submit</span>
          <PaperAirplane/>
        </button>
      </div>
    </form>
  </Layout>

</template>
