<template>
  <div>
    <label :for="id">{{ label }}</label>
    <input type="file" :id="id" @change="updateValue($event.target.files)" />
  </div>
</template>

<script>
export default {
  props: ['label', 'id'],
  methods: {
    updateValue(files) {
      this.$emit('input', files)
    }
  }
}
</script>
