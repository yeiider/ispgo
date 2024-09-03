<template>
  <div class="text-center p-4">
    <div class="text-xl font-bold">{{ currentTime }}</div>
    <div class="text-md">{{ currentDate }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const currentTime = ref('');
const currentDate = ref('');

const updateTime = () => {
  const now = new Date();
  currentTime.value = now.toLocaleTimeString(); // Obtiene la hora actual con segundos
  currentDate.value = now.toLocaleDateString(); // Obtiene la fecha actual
};

onMounted(() => {
  updateTime(); // Actualizar inmediatamente al montar el componente
  const interval = setInterval(updateTime, 1000); // Actualiza cada segundo

  onUnmounted(() => {
    clearInterval(interval); // Limpia el intervalo cuando el componente se desmonta
  });
});
</script>

<style scoped>
/* Estilos opcionales para mejorar la apariencia */
.text-center {
  text-align: center;
}
.text-xl {
  font-size: 1.25rem;
}
.text-md {
  font-size: 1rem;
}
.font-bold {
  font-weight: bold;
}
.p-4 {
  padding: 1rem;
}
</style>
