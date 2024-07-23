<template>
  <div class="space-y-2 md:flex @md/modal:flex md:flex-row @md/modal:flex-row md:space-y-0 @md/modal:space-y-0 py-5">
    <div class="w-full px-6 md:mt-2 @md/modal:mt-2 md:px-8 @md/modal:px-8 md:w-1/5 @md/modal:w-1/5">
      <label :for="field.uniqueKey" class="inline-block leading-tight space-x-1">
        <span>{{ field.name }}</span>
        <span v-if="field.required" class="text-red-500 text-sm">*</span>
      </label>
    </div>
    <div class="w-full space-y-2 px-6 md:px-8 @md/modal:px-8 md:w-3/5 @md/modal:w-3/5">
      <div class="space-y-1">
        <input
          type="file"
          :id="id"
          :name="field.attribute"
          :value="field.value"
          @input="updateValue($event.target.files)"/>


        <div id="app">
          <file-pond
            :name="field.attribute"
            ref="pond"
            label-idle="Drop file here..."
            v-bind:allow-multiple="false"
            :accepted-file-types="field.acceptedTypes"
            :server="server"
            v-bind:files="files"
            v-on:init="handleFilePondInit"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

export default {
  props: {
    label: String,
    id: String,
    field: Object,
    section: String
  },
  mounted() {

  },
  data: function () {
    return {
      files: ["cat.jpeg"],
      section: this.section,
      value: this.field.value,

      server: {
        process(fieldName,
                file,
                metadata,
                load,
                error,
                progress) {

          let fileKey = (
            [1e7] +
            -1e3 +
            -4e3 +
            -8e3 +
            -1e11
          ).replace(/[018]/g, (c) =>
            (
              c ^
              (crypto.getRandomValues(new Uint8Array(1))[0] &
                (15 >> (c / 4)))
            ).toString(16),
          )

          let formData = new FormData();
          console.log(file)
          formData.append('file', file, file.name);
          formData.append('fileKey', fileKey);
          formData.append('fieldName', fieldName)

          Nova.request().post('/settings-manager/settings/upload', formData, {
            onUploadProgress: (event) => {
              progress(event.lengthComputable, event.loaded, event.total);
            },
          })
            .then((response) => {
              console.log(response)
              load(response.data.fileKey);
            })
            .catch((err) => {
              error('Something went wrong');
            });
        },
        load(source, load) {
          console.log(source, load)
        }
      }
    };
  },
  components: {
    FilePond,
  },
  methods: {
    updateValue(value) {
      this.$emit('input', {key: this.field.uniqueKey, value});
    },

    handleFilePondInit: function () {
      console.log("FilePond has initialized");

      // FilePond instance methods are available on `this.$refs.pond`
    },
  }
}
</script>
