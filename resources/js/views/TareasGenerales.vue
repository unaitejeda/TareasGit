<!-- resources/js/views/TareasGenerales.vue -->
<template>
    <div>
      <h1>Todas las tareas</h1>
      <ul>
        <li v-for="tarea in tareas" :key="tarea.id">
          <div>
            <h3>{{ tarea.tarea }}</h3>
            <p>{{ tarea.descripcion }}</p>
            <!-- <p>Estado: {{ tarea.estado.nombre }}</p> -->
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        tareas: [],
      };
    },
    created() {
      this.fetchTareasGenerales();
    },
    methods: {
      async fetchTareasGenerales() {
        try {
          const response = await axios.get('/api/tareas-generales');
          this.tareas = response.data;
        } catch (error) {
          console.error('Error al obtener las tareas', error);
        }
      },
    },
  };
  </script>
  