<template>
    <div class="relative z-0 w-full mb-5">
        <select v-model="ubicacion" class="form-control pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none z-1 focus:outline-none focus:ring-0 focus:border-black border-gray-200">
          <option value="">Selecciona una ubicación</option>
          <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
        </select>
      </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const ubicacion = ref('');
  const cities = ref([]);
  
  onMounted(() => {
    // Llamar a la API para obtener las ciudades cuando el componente se monta
    fetchCities();
  });
  
  const fetchCities = async () => {
    try {
      const response = await fetch('/getCities');
      const data = await response.json();
      cities.value = data;
    } catch (error) {
      console.error('Error al obtener las ciudades:', error);
    }
  };
  </script>
  