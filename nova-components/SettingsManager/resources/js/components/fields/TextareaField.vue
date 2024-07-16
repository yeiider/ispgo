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
        <textarea
          :id="id"
          :value="value"
          :name="field.attribute"
          @input="updateValue($event.target.value)"
          class="w-full form-control form-input form-control-bordered py-2"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: String,
    label: String,
    id: String,
    field: Object
  },
  methods: {
    updateValue(value) {
      this.$emit('input', {key: this.field.uniqueKey, value});
    }
  }
}
</script>
