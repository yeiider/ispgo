<template>
  <div>
    <label :for="id">{{ label }}</label>
    <textarea :id="id" :value="value" @input="updateValue($event.target.value)"/>
  </div>
</template>

<script>
export default {
  props: ['value', 'label', 'id'],
  methods: {
    updateValue(value) {
      this.$emit('input', value)
    }
  }
}
</script>
