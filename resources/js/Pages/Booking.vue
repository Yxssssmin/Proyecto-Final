<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';

// Creamos variables reactivas para almacenar los datos del formulario
const nombreReserva = ref('');
const tipoComida = ref('');
const tiposDeComida = ref([]);
const ubicacion = ref('');
const cities = ref([]);
const numeroPersonas = ref(0);
const numeroContacto = ref('');
const fechaReserva = ref('');
const horaReserva = ref('');

// Función para manejar el envío del formulario
const handleSubmit = async () => {
    if (!isFormValid.value) {
        console.log('Por favor, completa todos los campos requeridos.');
        return;
    }

    try {
        const response = await fetch('/reservas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombreReserva: nombreReserva.value,
                tipoComida: tipoComida.value,
                ubicacion: ubicacion.value,
                numeroPersonas: numeroPersonas.value,
                numeroContacto: numeroContacto.value,
                fechaReserva: fechaReserva.value,
                horaReserva: horaReserva.value
            }),
        });

        if (response.ok) {
            console.log('Reserva creada exitosamente');
            // Aquí puedes redirigir al usuario a una página de éxito o realizar otras acciones
        } else {
            console.error('Error al crear la reserva:', response.statusText);
        }
    } catch (error) {
        console.error('Error al crear la reserva:', error);
    }
};

// Computed property para verificar la validez del formulario
const isFormValid = computed(() => {
    return (
        nombreReserva.value !== '' &&
        tipoComida.value !== '' &&
        ubicacion.value !== '' &&
        fechaReserva.value !== '' &&
        horaReserva.value !== '' &&
        numeroPersonas.value > 0 &&
        numeroContacto.value !== ''
    );
});

// Función para manejar la selección de la ubicación
const handleLocationSelect = (suggestion) => {
    // Actualizar el valor de la ubicación en el formulario
    ubicacion.value = suggestion.description;
};

// Obtener las ciudades y tipos de comida cuando el componente se monta
onMounted(() => {
    fetchCities();
    obtenerTiposDeComida();
});

// Lógica para obtener la lista de ciudades desde el backend
const fetchCities = async () => {
    try {
        const response = await fetch('/getCities');
        const data = await response.json();
        cities.value = data;
    } catch (error) {
        console.error('Error al obtener las ciudades:', error);
    }
};

// Lógica para obtener la lista de tipos de comida desde el backend
const obtenerTiposDeComida = async () => {
    try {
        const response = await fetch('/obtenerTiposDeComida');
        const data = await response.json();
        tiposDeComida.value = data;
    } catch (error) {
        console.error('Error al obtener los tipos de comida:', error);
    }
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
                        <span class="text-sm text-red-600 hidden" id="error">Nombre es requerido</span>
                    </div>

                    <!-- Agregamos radio buttons para el tipo de comida -->
                    <div class="relative z-0 w-full mb-5">
                        <select v-model="tipoComida" class="form-control pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none z-1 focus:outline-none focus:ring-0 focus:border-black border-gray-200" required>
                            <option value="">Selecciona el estilo de cocina</option>
                            <option v-for="comida in tiposDeComida" :key="comida" :value="comida">{{ comida }}</option>
                        </select>
                        <span class="text-sm text-red-600 hidden" id="error">Tipo de comida es requerido</span>
                    </div>

                    <!-- Agregamos un campo de texto para la ubicación -->
                    <div class="relative z-0 w-full mb-5">
                        <select v-model="ubicacion" class="form-control pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none z-1 focus:outline-none focus:ring-0 focus:border-black border-gray-200" required>
                            <option value="">Selecciona una ubicación</option>
                            <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                        </select>
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
                        :disabled="!isFormValid" 
                        class="w-full px-6 py-3 mt-3 text-lg text-white transition-all duration-150 ease-linear rounded-lg shadow outline-none bg-blue-500 hover:bg-blue-600 hover:shadow-lg focus:outline-none"
                    >
                        Reservar
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
