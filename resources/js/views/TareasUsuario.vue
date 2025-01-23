<template>
  <div class="tasks-container">
    <h1 class="title">Mis Tareas</h1>
    <div v-if="tareas.length === 0" class="empty-message">No tienes tareas.</div>
    <div v-else class="tasks-grid">
      <div class="task-card" v-for="tarea in tareas" :key="tarea.id">
        <h2 class="task-title">{{ tarea.tarea }}</h2>
        <p class="task-description">{{ tarea.descripcion }}</p>
        <p class="task-status">Estado: <span :class="'status-' + tarea.estado.estado.toLowerCase()">{{ tarea.estado.estado }}</span></p>
        <button
          v-if="tarea.estado.estado === 'programada'"
          @click="finalizarTarea(tarea.id)"
          class="btn-success"
        >
          Finalizar Tarea
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      tareas: [],
    };
  },
  created() {
    this.cargarTareas();
  },
  methods: {
    async cargarTareas() {
      try {
        const response = await axios.get("/api/tareas-usuario");
        this.tareas = response.data;
      } catch (error) {
        console.error("Error al cargar las tareas:", error);
      }
    },
    async finalizarTarea(id) {
      try {
        await axios.put(`/api/tareas/finalizar/${id}`);
        alert("La tarea se ha finalizado correctamente.");
        this.cargarTareas();
      } catch (error) {
        console.error("Error al finalizar la tarea:", error);
        alert("Hubo un error al finalizar la tarea.");
      }
    },
  },
};
</script>

<style scoped>
.tasks-container {
  padding: 20px;
  max-width: 800px;
  margin: auto;
}
.title {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 20px;
}
.empty-message {
  text-align: center;
  color: #777;
  font-size: 1.2rem;
}
.tasks-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}
.task-card {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.task-title {
  font-size: 1.5rem;
  margin-bottom: 10px;
}
.task-description {
  margin-bottom: 10px;
  color: #555;
}
.task-status {
  margin-bottom: 15px;
  font-weight: bold;
}
.status-programada {
  color: #007bff;
}
.status-finalizada {
  color: #28a745;
}
.btn-success {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 4px;
  cursor: pointer;
}
.btn-success:hover {
  background-color: #218838;
}
</style>
