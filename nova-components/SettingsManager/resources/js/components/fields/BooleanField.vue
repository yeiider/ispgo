<template>
  <div class="space-y-2 md:flex @md/modal:flex md:flex-row @md/modal:flex-row md:space-y-0 @md/modal:space-y-0 py-5">
    <div class="w-full px-6 md:mt-2 @md/modal:mt-2 md:px-8 @md/modal:px-8 md:w-1/5 @md/modal:w-1/5">
      <label :for="field.uniqueKey" class="inline-block leading-tight space-x-1">
        <span>{{ field.name }}</span>
        <span v-if="field.required" class="text-red-500 text-sm">*</span>
      </label>
    </div>
    <div class="w-full space-y-2 px-6 md:px-8 @md/modal:px-8 md:w-3/5 @md/modal:w-3/5 flex">
      <input
        type="checkbox"
        :id="field.uniqueKey"
        :name="field.attribute"
        @input="updateValue($event.target.value)"
        :checked="checked"
        :disabled="readOnly"
      />
    </div>
  </div>
</template>
<script>
export default {
  name: "BooleanField",
  props: {
    field: Object
  },
  methods: {
    updateValue(value) {
      value = value === 'on';
      this.$emit('input', {key: this.field.uniqueKey, value});
    }
  }
}
</script>


