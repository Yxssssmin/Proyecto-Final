<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  name: 'BarChart',
  components: { Bar },
  data() {
    return {
      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'Cantidad de Reservas',
            backgroundColor: ['#42A5F5', '#FF6384'],
            data: []
          }
        ]
      },
      loaded: false
    }
  },
  methods: {
    fetchData() {
      axios.get('/total-reservas')
      .then(response => {
        this.processData(response.data);
      })
      .catch(error => {
        console.error("Error al obtener los datos de reservas:", error);
      });
    },
    processData(data) {
      const reservasPorUsuario = {};
      
      data.forEach(reserva => {
        const idUsuario = reserva.id_usuario;
        if (!reservasPorUsuario[idUsuario]) {
          reservasPorUsuario[idUsuario] = 0;
        }
        reservasPorUsuario[idUsuario]++;
      });
      
      this.datacollection.labels = Object.keys(reservasPorUsuario);
      this.datacollection.datasets[0].data = Object.values(reservasPorUsuario);

      this.loaded = true; // Indica que los datos se han cargado
    }
  },
  mounted() {
    this.fetchData();
  }
}
</script>

<template>
  <div class="container reservations-chart">
    <Bar v-if="loaded" :data="datacollection" />
  </div>
</template>

<style scoped>

.reservations-chart {
  max-width: 50%; /* Establece el ancho máximo */
  margin-top: 5rem;
  margin-left: 5rem;
}

</style>
