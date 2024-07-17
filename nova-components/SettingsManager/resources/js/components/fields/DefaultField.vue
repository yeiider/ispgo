<template>
  <div class="flex flex-col gap-5">
    <template v-for="field in fields" :key="field.uniqueKey">
      <component
        :is="render(field.component)"
        :id="field.uniqueKey"
        :value="field.value"
        :field="field"
        :label="field.name"
        @input="handleFieldUpdate"
      />
    </template>
  </div>
</template>
<script>
import SelectField from './SelectField.vue'
import TextField from "./TextField.vue";
import TextareaField from "./TextareaField.vue";
import FileField from "./FileField.vue";
import BooleanField from './BooleanField.vue';
import PasswordField from './PasswordField.vue';
import DateField from './DateField.vue'

export default {
  name: "DefaultField",
  props: {
    fields: Object
  },
  components: {
    'select-field': SelectField,
    'text-field': TextField,
    'textarea-field': TextareaField,
    'file-field': FileField,
    'boolean-field': BooleanField,
    'password-field': PasswordField,
    'date-field': DateField,

  },
  data() {
    return {
      fieldTypes: [
        'select-field',
        'text-field',
        'textarea-field',
        'file-field',
        'boolean-field',
        'password-field',
        'date-field',
      ]
    }
  },

  methods: {
    render: function (fieldType) {
      if (this.fieldTypes.includes(fieldType)) {
        return fieldType;
      }
      return undefined;
    },

    handleFieldUpdate({key, value}) {
      if (typeof key === "undefined" && typeof value === "undefined") return;

      this.$emit('update-field', {key, value});
    }
  }
}
</script>

