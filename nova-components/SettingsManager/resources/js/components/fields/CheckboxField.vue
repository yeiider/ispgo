<template>
  <div>
    <label :for="id">{{ label }}</label>
    <input type="checkbox" :id="id" :checked="value" @change="updateValue($event.target.checked)"/>
  </div>
</template>

<script>
export default {
  name: "CheckboxField",
  props: ['value', 'label', 'id'],
  methods: {
    updateValue(value) {
      this.$emit('input', value);
    }
  }
}
</script>


<style scoped>

</style>
