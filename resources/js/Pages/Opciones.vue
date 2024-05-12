<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { defineProps } from "vue";
import Header from "@/Components/Header.vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import IconPerson from "@/Components/IconPerson.vue";
import IconReserva from "@/Components/IconReserva.vue";
import IconViewReserva from "@/Components/IconViewReserva.vue";

// import { faUser, faKey, faServer } from '@fortawesome/free-solid-svg-icons';

const props = defineProps(["title", "links"]);
</script>

<template>
    <AppLayout title="Opciones">



    <h1 class="hr-lines"> ELIJA UNA OPCIÓN </h1>

    <section class="container mx-auto py-15">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="/mis-reservas">
          <div class="shadow-md hover:shadow-lg duration-300 p-8 rounded-md bg-slate-50">
            <div class="border rounded-full inline-block p-4 border-slate-800 mb-3">
              <IconViewReserva/>
            </div>
            <h3 class="text-2x1 font-bold text-slate-800 mb-3">Mis Reservas</h3>
            <p>Aquí puedes ver tus reservas.</p>
          </div>
        </a>
        <a href="/reservar">
          <div class="shadow-md hover:shadow-lg duration-300 p-8 rounded-md bg-slate-50">
            <div class="border rounded-full inline-block p-4 border-slate-800 mb-3">
              <IconReserva/>
            </div>
            <h3 class="text-2x1 font-bold text-slate-800 mb-3">Reservar restaurante</h3>
            <p>Haz una nueva reserva.</p>
          </div>
        </a> 
        <a :href="route('profile.show')">
        <div class="shadow-md hover:shadow-lg duration-300 p-8 rounded-md bg-slate-50">
            <div class="border rounded-full inline-block p-4 border-slate-800 mb-3">
              <IconPerson/>
            </div>
            <h3 class="text-2x1 font-bold text-slate-800 mb-3">Mi perfil</h3>
            <p>Ve o edita tu perfil.</p>
          </div>
        </a>
      </div>

    </section>
    </AppLayout>

</template>
