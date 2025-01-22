<template>
  <div>
    <h1>Tareas de {{ userName }}</h1>
    <ul>
      <li v-for="tarea in tareas" :key="tarea.id">
        <div>
          <h3>{{ tarea.tarea }}</h3>
          <p>{{ tarea.descripcion }}</p>
          <p>Estado: {{ tarea.estado }}</p>
          <button v-if="tarea.estado === 'programada'" @click="finalizarTarea(tarea.id)">
            Marcar como finalizada
          </button>
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
        if (this.tareas.length > 0) {
          this.userName = this.tareas[0].user.name;
        }
      } catch (error) {
        console.error('Error al obtener las tareas', error);
      }
    },
    async finalizarTarea(id) {
      try {
        await axios.put(`/api/tareas/finalizar/${id}`);
        const tarea = this.tareas.find(t => t.id === id);
        if (tarea) tarea.estado = 'finalizada';
      } catch (error) {
        console.error('Error al finalizar la tarea', error);
      }
    },
  },
};
</script>
