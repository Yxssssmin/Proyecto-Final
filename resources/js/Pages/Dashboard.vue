<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import SideBar from '@/Components/SideBar.vue';
import { Bar } from 'vue-chartjs';
import ReservationsChart from '@/Components/ReservationsChart.vue';
import RestaurantesPorCiudadChart from '@/Components/RestaurantesPorCiudadChart.vue';

</script>

<template>
  <Head title="Dashboard Admin" />
  <SideBar/>

  <div class="charts-container">
    <ReservationsChart />
    <RestaurantesPorCiudadChart />
  </div>
      
</template>


<style scoped>
.charts-container {
  display: flex; /* Establece el contenedor como flexbox */
  justify-content: space-between; /* Distribuye los elementos uniformemente */
  flex-wrap: wrap; /* Permite que los elementos se envuelvan */
}
</style>
