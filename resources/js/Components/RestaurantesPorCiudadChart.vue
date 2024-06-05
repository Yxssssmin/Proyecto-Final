<template>
    <div class="chart restaurantes-por-ciudad-chart">
      <Doughnut v-if="loaded" :data="datacollection" :options="chartOptions" />
    </div>
  </template>
  
  <script>
  import { Doughnut } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js';
  import axios from 'axios';
  
  ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale);
  
  export default {
    name: 'RestaurantesPorCiudadChart',
    components: { Doughnut },
    data() {
      return {
        datacollection: {
          labels: [],
          datasets: [
            {
              label: 'Cantidad de Restaurantes',
              backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
              ],
              data: []
            }
          ]
        },
        chartOptions: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  let label = tooltipItem.label || '';
                  if (label) {
                    label += ': ';
                  }
                  label += tooltipItem.raw;
                  return label;
                }
              }
            }
          }
        },
        loaded: false
      }
    },
    methods: {
      fetchData() {
        axios.get('/restaurantes-por-ciudad')
          .then(response => {
            this.processData(response.data);
          })
          .catch(error => {
            console.error("Error al obtener los datos de restaurantes por ciudad:", error);
          });
      },
      processData(data) {
        const labels = data.map(item => item.City);
        const datasetData = data.map(item => item.total_restaurantes);
  
        this.datacollection.labels = labels;
        this.datacollection.datasets[0].data = datasetData;
  
        this.loaded = true; // Indica que los datos se han cargado
      }
    },
    mounted() {
      this.fetchData();
    }
  }
  </script>
  
  <style scoped>
  .chart {
    flex: 1;
    max-width: 35%;
    margin: 1rem;
  }
  </style>
  