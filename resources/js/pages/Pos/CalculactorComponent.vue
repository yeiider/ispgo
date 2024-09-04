<template>
  <div class="flex">
    <div class="bg-white w-80">
      <div class="text-right text-3xl p-2 mb-3 border border-gray-300 rounded">
        {{ currentInput || '0' }}
      </div>
      <div class="grid grid-cols-4 mb-3 gap-2">
        <button @click="clear" class="col-span-2 p-4 bg-red-500 text-white rounded">AC</button>
        <button @click="append('%')" class="p-4 bg-gray-200 rounded">%</button>
        <button @click="append('/')" class="p-4 bg-blue-500 text-white rounded">/</button>

        <button @click="append('7')" class="p-4 bg-gray-200 rounded">7</button>
        <button @click="append('8')" class="p-4 bg-gray-200 rounded">8</button>
        <button @click="append('9')" class="p-4 bg-gray-200 rounded">9</button>
        <button @click="append('*')" class="p-4 bg-blue-500 text-white rounded">*</button>

        <button @click="append('4')" class="p-4 bg-gray-200 rounded">4</button>
        <button @click="append('5')" class="p-4 bg-gray-200 rounded">5</button>
        <button @click="append('6')" class="p-4 bg-gray-200 rounded">6</button>
        <button @click="append('-')" class="p-4 bg-blue-500 text-white rounded">-</button>

        <button @click="append('1')" class="p-4 bg-gray-200 rounded">1</button>
        <button @click="append('2')" class="p-4 bg-gray-200 rounded">2</button>
        <button @click="append('3')" class="p-4 bg-gray-200 rounded">3</button>
        <button @click="append('+')" class="p-4 bg-blue-500 text-white rounded">+</button>

        <button @click="append('0')" class="col-span-2 p-4 bg-gray-200 rounded">0</button>
        <button @click="append('.')" class="p-4 bg-gray-200 rounded">.</button>
        <button @click="calculate" class="p-4 bg-green-500 text-white rounded">=</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue';

// Definir el estado de la calculadora
const currentInput = ref('');

// Función para agregar caracteres a la entrada actual
const append = (character) => {
  currentInput.value += character;
};

// Función para limpiar la entrada actual
const clear = () => {
  currentInput.value = '';
};

// Función para evaluar la expresión matemática
const calculate = () => {
  try {
    currentInput.value = eval(currentInput.value).toString();
  } catch {
    currentInput.value = 'Error';
  }
};
</script>

<style scoped>
/* Estilos personalizados si son necesarios */
</style>
