<template>
  <div>
    <h1>Tareas de {{ userName }}</h1>
    <ul>
      <li v-for="tarea in tareas" :key="tarea.id">
        <div>
          <h3>{{ tarea.tarea }}</h3>
          <p>{{ tarea.descripcion }}</p>
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
      userName: '',
    };
  },
  created() {
    this.fetchTareasUsuario();
  },
  methods: {
    async fetchTareasUsuario() {
      try {
        const response = await axios.get('/api/tareas-usuario');
        this.tareas = response.data;

        if (this.tareas.length > 0 && this.tareas[0].user) {
          this.userName = this.tareas[0].user.name; // Mostrar nombre del usuario si está disponible
        } else {
          this.userName = 'Usuario desconocido'; // Valor predeterminado si no hay datos del usuario
        }
      } catch (error) {
        console.error('Error al obtener las tareas', error);
      }
    },
  },
};
</script>
