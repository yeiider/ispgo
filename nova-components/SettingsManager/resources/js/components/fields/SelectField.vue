<template>
  <div>
    <label :for="id">{{ label }}</label>
    <select :id="id" :value="value" @change="updateValue($event.target.value)">
      <option v-for="(option, index) in options" :key="index" :value="option.value">{{ option.label }}</option>
    </select>
  </div>
</template>


<script>
export default {
  name: "SelectField",
  props: ['value', 'label', 'id', 'options'],
  methods: {
    updateValue(value) {
      this.$emit('input', value);
    }
  }
}
</script>
