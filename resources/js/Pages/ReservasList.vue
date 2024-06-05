<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';

const reservas = ref([])
const perPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')
const selectedCity = ref('All')
const isModalVisible = ref(false)
const currentreservaId = ref(null)
const isEditModalVisible = ref(false)
const editedreserva = ref({ Name: '', City: ''})

const fetchReservas = async () => {
  try {
    const response = await axios.get('/reservas-guardadas') // Adjust API endpoint as necessary
    reservas.value = response.data
  } catch (error) {
    console.error('Error fetching reservas:', error)
  }
}

onMounted(fetchReservas)

const filteredReservas = computed(() => {
  let result = reservas.value;

  if (searchQuery.value) {
    result = result.filter(reserva =>
      reserva.nombre_reserva.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  if (selectedCity.value !== 'All') {
    result = result.filter(reserva => reserva.City === selectedCity.value);
  }

  return result;
});


const paginatedreservas = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  const end = start + perPage.value
  return filteredReservas.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredReservas.value.length / perPage.value)
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const statusClass = (status) => {
  switch (status) {
    case 'activo':
      return 'text-green-900'
    case 'finalizado':
      return 'text-red-900'
    default:
      return ''
  }
}

const statusBackgroundClass = (status) => {
  switch (status) {
    case 'activo':
      return 'bg-green-200'
    case 'finalizado':
      return 'bg-red-200'
    default:
      return ''
  }
}

const editreserva = (reserva) => {
  editedreserva.value = { ...reserva }
  isEditModalVisible.value = true
}

const hideEditModal = () => {
  isEditModalVisible.value = false
  editedreserva.value = { Name: '', City: '' }
}

const updatereserva = async () => {
  try {
    const { id, nombre_reserva, numero_personas, numero_contacto, fecha_reserva } = editedreserva.value;
    await axios.put(`/reservasDashboard/${id}`, { nombre_reserva, numero_personas, numero_contacto, fecha_reserva }); // Ajusta el punto final de la API según sea necesario
    fetchReservas(); // Llama a la función para volver a cargar las reservas después de la actualización
    hideEditModal(); // Oculta el modal de edición
  } catch (error) {
    console.error('Error actualizando reserva:', error);
  }
}


const confirmDelete = (reservaId) => {
  currentreservaId.value = reservaId
  isModalVisible.value = true
}

const hideModal = () => {
  isModalVisible.value = false
  currentreservaId.value = null
}

const deletereserva = async (reservaId) => {
  try {
    await axios.delete(`/reservasDashboard/${reservaId}`) // Adjust API endpoint as necessary
    reservas.value = reservas.value.filter(reserva => reserva.id !== reservaId)
    hideModal()
  } catch (error) {
    console.error('Error deleting reserva:', error)
  }
}
</script>

<template>
  <Head title="Dashboard Admin" />
  <AppLayout title="Reservar">

  <div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
      <div>
        <h2 class="text-2xl font-semibold leading-tight text-center">MIS RESERVAS</h2>
      </div>
      <div class="my-2 flex sm:flex-row flex-col">
        <div class="flex flex-row mb-1 sm:mb-0">
          <div class="relative">
            <select v-model="perPage" class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
              </svg>
            </div>
          </div>
          <div class="relative">
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
          <input v-model="searchQuery" placeholder="Buscar nombre" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
        </div>
        
      </div>
      <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
          <table class="min-w-full leading-normal">
            <thead>
              <tr>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo de comida</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ciudad</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nº de personas</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contacto</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                <th class="px-10 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="reserva in paginatedreservas" :key="reserva.id">
                <td class="px-10 py-5 border-b border-gray-200 bg-white text-sm">
                  <div class="flex items-center">
                    <div class="ml-3">
                      <p class="text-gray-900 whitespace-no-wrap">{{ reserva.id }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.nombre_reserva }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.tipo_comida }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.ciudad }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.numero_personas }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.numero_contacto }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <span :class="statusClass(reserva.estado_reserva)" class="relative inline-block px-3 py-1 font-semibold leading-tight">
                    <span aria-hidden class="absolute inset-0 opacity-50 rounded-full" :class="statusBackgroundClass(reserva.estado_reserva)"></span>
                    <span class="relative">{{ reserva.estado_reserva }}</span>
                  </span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ reserva.fecha_reserva }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <div class="flex space-x-2 mt-2">
                    <button @click="editreserva(reserva)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                      Editar
                    </button>
                    <button @click="confirmDelete(reserva.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                      Borrar
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            <span class="text-xs xs:text-sm text-gray-900">Showing {{ (currentPage - 1) * perPage + 1 }} to {{ Math.min(currentPage * perPage, filteredReservas.length) }} of {{ filteredReservas.length }} Entries</span>
            <div class="inline-flex mt-2 xs:mt-0">
              <button @click="prevPage" :disabled="currentPage === 1" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-l">Prev</button>
              <button @click="nextPage" :disabled="currentPage === totalPages" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-r">Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal delete -->
  <TransitionRoot as="template" :show="isModalVisible">
    <Dialog class="relative z-10" @close="isModalVisible = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <ExclamationTriangleIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                  </div>
                  <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Eliminar reserva</DialogTitle>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500">¿Estás seguro de que quieres eliminar esta reserva? Todos los datos de la reserva se eliminarán permanentemente. Esta acción no se puede deshacer.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="deletereserva(currentreservaId)">Eliminar</button>
                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="hideModal" ref="cancelButtonRef">Cancelar</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Modal edit -->
  <TransitionRoot as="template" :show="isEditModalVisible">
    <Dialog class="relative z-10" @close="isEditModalVisible = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>
  
      <div class="fixed inset-0 z-10 flex items-center justify-center overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg" style="width: 400px;">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900 text-center">Editar reserva</DialogTitle>
                    <div class="mt-2">
                      <label for="edit-name" class="text-sm font-medium text-gray-700">Nombre de la reserva</label>
                      <input id="edit-name" v-model="editedreserva.nombre_reserva" placeholder="Nombre" class="appearance-none rounded-md border border-gray-400 block pl-4 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none mb-2" />
  
                      <label for="edit-numero-personas" class="text-sm font-medium text-gray-700">Número de personas</label>
                      <input id="edit-numero-personas" v-model="editedreserva.numero_personas" placeholder="Número de personas" type="number" class="appearance-none rounded-md border border-gray-400 block pl-4 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none mb-2" />
  
                      <label for="edit-numero-contacto" class="text-sm font-medium text-gray-700">Número de contacto</label>
                      <input id="edit-numero-contacto" v-model="editedreserva.numero_contacto" placeholder="Número de contacto" type="tel" class="appearance-none rounded-md border border-gray-400 block pl-4 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none mb-2" />
  
                      <label for="edit-fecha-reserva" class="text-sm font-medium text-gray-700">Fecha de reserva</label>
                      <input id="edit-fecha-reserva" v-model="editedreserva.fecha_reserva" placeholder="Fecha de reserva" type="date" class="appearance-none rounded-md border border-gray-400 block pl-4 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none mb-2" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto" @click="updatereserva">Guardar</button>
                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="hideEditModal" ref="cancelEditButtonRef">Cancelar</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
</AppLayout>
</template>


<style>
/* Optional additional styles */
</style>
