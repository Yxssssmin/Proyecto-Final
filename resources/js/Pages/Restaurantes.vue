<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const restaurants = ref([]);
const perPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref('');
const selectedCity = ref('All');
const selectedCuisine = ref('All');
const cities = ref([]);
const cuisines = ref([]);

const fetchRestaurants = async () => {
  try {
    const response = await axios.get('/api/restaurantes');
    restaurants.value = response.data;
    cities.value = [...new Set(restaurants.value.map(restaurant => restaurant.city))];
    cuisines.value = [...new Set(restaurants.value.map(restaurant => restaurant.Cuisine_Style))];
  } catch (error) {
    console.error('Error fetching restaurants:', error);
  }
};

onMounted(fetchRestaurants);

const filteredRestaurants = computed(() => {
  let result = restaurants.value;

  if (searchQuery.value) {
    result = result.filter(restaurant =>
      restaurant.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      restaurant.city.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  if (selectedCity.value !== 'All') {
    result = result.filter(restaurant => restaurant.city === selectedCity.value);
  }

  if (selectedCuisine.value !== 'All') {
    result = result.filter(restaurant => restaurant.Cuisine_Style === selectedCuisine.value);
  }

  return result;
});

const paginatedRestaurants = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  const end = start + perPage.value;
  return filteredRestaurants.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredRestaurants.value.length / perPage.value);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const modalOpen = ref(false);
const selectedRestaurant = ref({}); // Estado para almacenar los detalles del restaurante seleccionado

const openModal = (restaurant) => {
  selectedRestaurant.value = restaurant;
  modalOpen.value = true;
};

const closeModal = () => {
  modalOpen.value = false;
};

</script>

<template>
  <Head title="Lista de Restaurantes" />
  <AppLayout title="Restaurantes">
    <div class="container mx-auto px-4 sm:px-8">
      <div class="py-8">
        <div>
          <h2 class="text-2xl font-semibold leading-tight text-center">LISTA DE RESTAURANTES</h2>
        </div>
        <div class="my-2 flex sm:flex-row flex-col">
          <div class="flex flex-row mb-1 sm:mb-0">
            <div class="relative">
              <select v-model="selectedCuisine" class="appearance-none h-full rounded-r border-t sm:rounded-r-none sm:border-r-0 border-r border-b block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-500">
                <option value="All">Todos</option>
                <option v-for="cuisine in cuisines" :key="cuisine" :value="cuisine">{{ cuisine }}</option>
              </select>              
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <div class="block relative">
            <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
              <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z"></path>
              </svg>
            </span>
            <input v-model="searchQuery" placeholder="Buscar por nombre o ciudad" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
          </div>
        </div>
        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
          <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
              <thead>
                <tr>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Id</th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ciudad</th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                  <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="restaurant in paginatedRestaurants" :key="restaurant.Id">
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ restaurant.Id }}</p>
                  </td>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ restaurant.Name }}</p>
                  </td>

                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ restaurant.City }}</p>
                  </td>
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <div class="flex flex-row">
                        <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="{'text-yellow-300': i <= restaurant.Rating, 'text-gray-200 dark:text-gray-600': i > restaurant.Rating}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>
                </td>                
                  <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <button @click="openModal(restaurant)" class="flex items-center text-white bg-blue-600 hover:bg-blue-900 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
              <span class="text-xs xs:text-sm text-gray-900">Mostrando {{ (currentPage - 1) * perPage + 1 }} a {{ Math.min(currentPage * perPage, filteredRestaurants.length) }} de {{ filteredRestaurants.length }} resultados</span>
              <div class="inline-flex mt-2 xs:mt-0">
                <button @click="prevPage" :disabled="currentPage === 1" class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-l">Anterior</button>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-r">Siguiente</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <transition name="modal">
    <div class="fixed inset-0 overflow-y-auto" v-if="modalOpen">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <transition
          enter-active-class="ease-out duration-300"
          enter-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-200"
          leave-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
        </transition>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <transition
          enter-active-class="ease-out duration-300"
          enter-class="transform opacity-0 scale-95"
          enter-to-class="transform opacity-100 scale-100"
          leave-active-class="ease-in duration-200"
          leave-class="transform opacity-100 scale-100"
          leave-to-class="transform opacity-0 scale-95"
        >
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="w-full h-8 object-cover object-center rounded-t-lg"></div>
            <div class="px-5 pb-5">
              <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ selectedRestaurant.Name }}</h5>
              <div class="flex items-center mt-2.5 mb-5">
                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                  <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="{'text-yellow-300': i <= selectedRestaurant.Rating, 'text-gray-200 dark:text-gray-600': i > selectedRestaurant.Rating}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                  </svg>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3">{{ selectedRestaurant.Rating }}</span>
              </div>
              <div class="flex items-center mb-5">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Cantidad de votos: 
                  <span class="text-gray-700 dark:text-gray-300 text-sm">{{ selectedRestaurant.Number_of_Reviews }} votos</span>
                </h4>
                
              </div>
              <div class="mb-5">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12 2a7 7 0 00-7 7c0 4.335 7 13 7 13s7-8.665 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" clip-rule="evenodd"></path>
                  </svg>
                  <span class="text-gray-700 dark:text-gray-300 text-sm">{{ selectedRestaurant.City }}</span>
                </div>
              </div>
              <div class="mt-5 space-y-4">
                <h6 class="text-lg font-medium text-gray-900 dark:text-white">Reseñas</h6>
                <div v-for="(review, index) in selectedRestaurant.Reviews" :key="index" class="flex items-start space-x-2">
                  <div class="flex-shrink-0">
                    <div class="inline-block bg-blue-500 rounded-full h-8 w-8 text-center text-white font-bold">{{ review.content.charAt(0) }}</div>
                  </div>
                  <div>
                    <p class="text-gray-900 dark:text-white"><strong>{{ review.content }}</strong></p>
                    <p class="text-gray-500 text-sm dark:text-gray-400">{{ review.date }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
              <button type="button" @click="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                Cerrar
              </button>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </transition>
  
  
</template>

<style scoped>
/* Agrega cualquier estilo adicional aquí */
</style>
