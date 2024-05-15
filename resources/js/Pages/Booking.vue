<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue'; // Importamos ref para manejar los datos del formulario

// Creamos variables reactivas para almacenar los datos del formulario
const nombreReserva = ref('');
const tipoComida = ref('');
const ubicacion = ref('');
const numeroPersonas = ref(0);
const numeroContacto = ref('');
const fechaReserva = ref('');
const horaReserva = ref('');

// Función para manejar el envío del formulario
const handleSubmit = () => {
  // Aquí puedes agregar la lógica para enviar los datos del formulario a través de una solicitud HTTP
  console.log('Datos del formulario:', {
    nombreReserva: nombreReserva.value,
    tipoComida: tipoComida.value,
    ubicacion: ubicacion.value,
    numeroPersonas: numeroPersonas.value,
    numeroContacto: numeroContacto.value,
    fechaReserva: fechaReserva.value,
    horaReserva: horaReserva.value
  });
};
</script>

<template>
  <AppLayout title="Reservar">
    <div class="min-h-screen bg-gray-100 p-0 sm:p-12">
        <div class="mx-auto max-w-md px-6 py-12 bg-white border-0 shadow-lg sm:rounded-3xl">
          <h1 class="reserva font-mono text-2xl font-bold mb-8">Reserva de Restaurantes</h1>
          <form @submit.prevent="handleSubmit" novalidate>
            <div class="relative z-0 w-full mb-5">
              <input
                v-model="nombreReserva"
                type="text"
                name="name"
                placeholder=" "
                required
                class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
              />
              <label for="name" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Nombre a reservar</label>
              <span class="text-sm text-red-600 hidden" id="error">Name is required</span>
            </div>

            <!-- Agregamos radio buttons para el tipo de comida -->
            <fieldset class="relative z-0 w-full p-px mb-5">
              <legend class="absolute text-gray-500 transform scale-65 -top-1 origin-0">Tipo de comida</legend>
              <div class="block pt-7 pb-2 space-x-4">
                <label>
                  <input
                    v-model="tipoComida"
                    type="radio"
                    name="tipoComida"
                    value="Mediterraneo"
                    class="mr-2 text-black border-2 border-gray-300 focus:border-gray-300 focus:ring-black"
                  />
                  Mediterráneo
                </label>
                <label>
                  <input
                    v-model="tipoComida"
                    type="radio"
                    name="tipoComida"
                    value="Vegetariano"
                    class="mr-2 text-black border-2 border-gray-300 focus:border-gray-300 focus:ring-black"
                  />
                  Vegetariano
                </label>
                <label>
                  <input
                    v-model="tipoComida"
                    type="radio"
                    name="tipoComida"
                    value="Italiano"
                    class="mr-2 text-black border-2 border-gray-300 focus:border-gray-300 focus:ring-black"
                  />
                  Italiano
                </label>
              </div>
              <span class="text-sm text-red-600 hidden" id="error">Tipo de comida es requerido</span>
            </fieldset>

            <!-- Agregamos un campo de texto para la ubicación -->
            <div class="relative z-0 w-full mb-5">
              <input
                v-model="ubicacion"
                type="text"
                name="ubicacion"
                placeholder=" "
                required
                class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
              />
              <label for="ubicacion" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Ubicación</label>
              <span class="text-sm text-red-600 hidden" id="error">Ubicación es requerida</span>
            </div>

            <!-- Agregamos campos de fecha y hora para la reserva -->
            <div class="flex flex-row space-x-4">
              <div class="relative z-0 w-full mb-5">
                <input
                  v-model="fechaReserva"
                  type="date"
                  name="fechaReserva"
                  placeholder=" "
                  required
                  class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="fechaReserva" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Fecha de reserva</label>
                <span class="text-sm text-red-600 hidden" id="error">Fecha de reserva es requerida</span>
              </div>
              <div class="relative z-0 w-full">
                <input
                  v-model="horaReserva"
                  type="time"
                  name="horaReserva"
                  placeholder=" "
                  required
                  class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
                />
                <label for="horaReserva" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Hora de reserva</label>
                <span class="text-sm text-red-600 hidden" id="error">Hora de reserva es requerida</span>
              </div>
            </div>

            <!-- Agregamos los demás campos -->
            <div class="relative z-0 w-full mb-5">
              <input
                v-model="numeroPersonas"
                type="number"
                name="numeroPersonas"
                placeholder=" "
                required
                class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
              />
              <label for="numeroPersonas" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Número de personas</label>
              <span class="text-sm text-red-600 hidden" id="error">Número de personas es requerido</span>
            </div>

            <div class="relative z-0 w-full mb-5">
              <input
                v-model="numeroContacto"
                type="text"
                name="numeroContacto"
                placeholder=" "
                required
                class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200"
              />
              <label for="numeroContacto" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Número de contacto</label>
              <span class="text-sm text-red-600 hidden" id="error">Número de contacto es requerido</span>
            </div>

            <!-- Botón de enviar -->
            <button
              type="submit"
              class="w-full px-6 py-3 mt-3 text-lg text-white transition-all duration-150 ease-linear rounded-lg shadow outline-none bg-blue-500 hover:bg-blue-600 hover:shadow-lg focus:outline-none"
            >
              Reservar
            </button>
          </form>
        </div>
      </div>
    </AppLayout>
</template>
