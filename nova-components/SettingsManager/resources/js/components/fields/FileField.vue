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
var self;

export default {
  props: {
    label: String,
    id: String,
    field: Object,
    section: String
  },
  mounted() {
    self = this;
    let value = this.field.value;
    if (value) {
      this.files = [{
        source: value,
        options: {
          type: 'local',
        }
      }]
    }
  },
  data: function () {
    return {
      files: [],
      section: this.section,

      server: {
        process(fieldName,
                file,
                metadata,
                load,
                error,
                progress) {

          let formData = new FormData();
          formData.append('file', file, file.name);

          Nova.request().post('/settings-manager/settings/upload', formData, {
            onUploadProgress: (event) => {
              progress(event.lengthComputable, event.loaded, event.total);
            },
          })
            .then((response) => {

              if (response.status === 200 && response.data) {
                if (self) {
                  self.updateValue(response.data.url);
                  load(response.data.url);
                }
              }
            })
            .catch((err) => {
              error('Something went wrong');
            });
        },

        remove(source, load) {

          let fileName = source.replace("/storage/uploads/", "");
          console.log(fileName)

          Nova.request().delete(`/settings-manager/settings/deleteFile/${fileName}`)
            .then((response) => {
              if (response.status === 200) {
                load();
                self.updateValue(null);
              }
            })
            .catch((err) => {
              console.log('Error deleting file:', err);
            });
        },

        load(source, load) {
          fetch(source)
            .then(response => response.blob())
            .then(blob => {
              let file = new File([blob], "", blob);
              load(file);
            })
            .catch(error => console.error(`Error in fetch: ${error}`));
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
    },
  }
}
</script>
