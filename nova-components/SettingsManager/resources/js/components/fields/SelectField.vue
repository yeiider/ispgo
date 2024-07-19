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
        <select
          :id="id"
          :value="value"
          :name="field.attribute"
          @change="updateValue($event.target.value)"
          class="w-full form-control form-input form-control-bordered"
        >
          <option value="" selected disabled v-if="field.placeholder">
            {{ field.placeholder }}
          </option>
          <option v-for="(option, index) in field.options" :key="index" :value="option.value">{{
              option.label
            }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: "SelectField",
  props: {
    id: String,
    value: String,
    label: String,
    options: Array,
    field: Object
  },
  methods: {
    updateValue(value) {
      this.$emit('input', {key: this.field.uniqueKey, value});
    }
  }
}
</script>

<style scoped>
select.form-input {
  appearance: auto;
}
</style>

