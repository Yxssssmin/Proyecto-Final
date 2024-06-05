<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  restaurant: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <!-- Comenta la línea de imagen si no tienes imágenes reales -->
    <!-- <img :src="restaurant.image" alt="Restaurant image" class="p-8 rounded-t-lg" /> -->
    <div class="px-5 pb-5">
      <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ restaurant.name }}</h5>
      <div class="flex items-center mt-2.5 mb-5">
        <div class="flex items-center space-x-1 rtl:space-x-reverse">
          <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="{'text-yellow-300': i <= restaurant.rating, 'text-gray-200 dark:text-gray-600': i > restaurant.rating}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
          </svg>
        </div>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3">{{ restaurant.rating }}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ restaurant.priceRange }}</span>
        <a :href="restaurant.url" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ver más</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Aquí puedes añadir estilos específicos para el componente si lo necesitas */
</style>
