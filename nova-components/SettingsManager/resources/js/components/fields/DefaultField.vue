<template>
  <div class="flex flex-col gap-5">
    <template v-for="field in fields" :key="field.uniqueKey">
      <component
        :is="render(field.component)"
        :id="field.uniqueKey"
        :value="field.value"
        :field="field"
        :label="field.name"
      />
    </template>
  </div>
</template>
<script>
import SelectField from './SelectField.vue'
import TextField from "./TextField.vue";
import TextareaField from "./TextareaField.vue";
import FileField from "./FileField.vue";

export default {
  name: "DefaultField",
  props: {
    fields: Object
  },
  components: {
    'select-field': SelectField,
    'text-field': TextField,
    'textarea-field': TextareaField,
    'file-field': FileField

  },
  data() {
    return {
      fieldTypes: [
        'select-field',
        'text-field',
        'textarea-field',
        'file-field'
      ]
    }
  },
  mounted() {
    console.log(this.fields)
  },

  methods: {
    render: function (fieldType) {
      if (this.fieldTypes.includes(fieldType)) {
        return fieldType;
      }
      return undefined;
    }
  }
}
</script>

