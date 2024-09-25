<script setup>
import {defineProps} from 'vue';

const props = defineProps({
  width: {
    type: String,
    default: '100%'
  },
  height: {
    type: String,
    default: '100%'
  },
  strokeColor: {
    type: String,
    default: 'currentColor'
  },
  strokeWidth: {
    type: Number,
    default: 1.5
  },
  fill: {
    type: String,
    default: 'none'
  },
  sizeClass: {
    type: String,
    default: 'size-5'
  }
});
</script>

<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :fill="fill"
    :width="width"
    :height="height"
    viewBox="0 0 24 24"
    :stroke-width="strokeWidth"
    :stroke="strokeColor"
    :class="sizeClass"
  >
    <slot></slot>
  </svg>
</template>
