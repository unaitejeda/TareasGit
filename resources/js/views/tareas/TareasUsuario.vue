<template>
  <div class="tasks-container">
    <h1 class="title">Mis Tareas</h1>

    <div v-if="location">
      <p>Ubicación detectada: {{ location.city }}, {{ location.country_name }}</p>
    </div>

    <div class="filters">
      <div class="filter-group">
        <label for="estado">Estado:</label>
        <select id="estado" v-model="filtros.estado" @change="guardarFiltros">
          <option value="">Todos</option>
          <option value="programada">Programada</option>
          <option value="finalizada">Finalizada</option>
        </select>
      </div>
      <div class="filter-group">
        <label for="busqueda">Búsqueda:</label>
        <input
          id="busqueda"
          type="text"
          placeholder="Buscar por título o descripción"
          v-model="filtros.busqueda"
          @input="guardarFiltros"
        />
      </div>
    </div>

    <div v-if="tareasFiltradas.length === 0" class="empty-message">
      No se encontraron tareas con los filtros seleccionados.
    </div>
    <div v-else class="tasks-grid">
      <div class="task-card" v-for="tarea in tareasFiltradas" :key="tarea.id">
        <h2 class="task-title">{{ tarea.tarea }}</h2>
        <p class="task-description">{{ tarea.descripcion }}</p>
        <p class="task-status">
          Estado: 
          <span :class="'status-' + tarea.estado.estado.toLowerCase()">
            {{ tarea.estado.estado }}
          </span>
        </p>

        <p class="task-location">
          📍 Creada en: {{ tarea.ubicacion || "Ubicación no disponible" }}
        </p>

        <button
          v-if="tarea.estado.estado === 'programada'"
          @click="finalizarTarea(tarea.id)"
          class="btn-success"
        >
          Finalizar Tarea
        </button>

        <button @click="eliminarTarea(tarea.id)" class="btn-danger">
          Eliminar Tarea
        </button>

        <button @click="abrirModalEditar(tarea)" class="btn-warning">
          Editar Tarea
        </button>
      </div>

    <div v-if="mostrarModal" class="modal-overlay">
      <div class="modal-content">
        <h2>Editar Tarea</h2>
        <form @submit.prevent="actualizarTarea">
          <div class="form-group">
            <label for="tarea">Título</label>
            <input id="tarea" v-model="tareaSeleccionada.tarea" type="text" required />
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" v-model="tareaSeleccionada.descripcion"></textarea>
          </div>
          <div class="form-group">
            <label for="fecha">Fecha</label>
            <input id="fecha" v-model="tareaSeleccionada.fecha" type="date" required />
          </div>
          <div class="modal-actions">
            <button type="submit" class="btn-success">Guardar</button>
            <button type="button" @click="cerrarModal" class="btn-danger">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import GeolocationService from '/services/GeolocationService';

export default {
  data() {
    return {
      tareas: [],
      mostrarModal: false,
      tareaSeleccionada: null,
      filtros: {
        estado: "",
        busqueda: "",
      },
      location: null,
    };
  },
  computed: {
    tareasFiltradas() {
      return this.tareas
        .filter((tarea) => {
          if (this.filtros.estado) {
            return tarea.estado.estado.toLowerCase() === this.filtros.estado;
          }
          return true;
        })
        .filter((tarea) => {
          if (this.filtros.busqueda) {
            const texto = this.filtros.busqueda.toLowerCase();
            return (
              tarea.tarea.toLowerCase().includes(texto) ||
              tarea.descripcion.toLowerCase().includes(texto)
            );
          }
          return true;
        });
    },
  },
  created() {
    this.cargarFiltros();
    this.cargarTareas();
    this.obtenerUbicacion();
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
    guardarFiltros() {
      localStorage.setItem("filtrosTareas", JSON.stringify(this.filtros));
    },
    cargarFiltros() {
      const filtrosGuardados = localStorage.getItem("filtrosTareas");
      if (filtrosGuardados) {
        this.filtros = JSON.parse(filtrosGuardados);
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
    async eliminarTarea(id) {
      try {
        await axios.delete(`/api/tareas/${id}`);
        alert("La tarea se ha eliminado correctamente.");
        this.cargarTareas();
      } catch (error) {
        console.error("Error al eliminar la tarea:", error);
        alert("Hubo un error al eliminar la tarea.");
      }
    },
    abrirModalEditar(tarea) {
      this.tareaSeleccionada = { ...tarea };
      this.mostrarModal = true;
    },
    cerrarModal() {
      this.mostrarModal = false;
      this.tareaSeleccionada = null;
    },
    async actualizarTarea() {
      try {
        const { id, tarea, descripcion, fecha } = this.tareaSeleccionada;
        await axios.put(`/api/tareas/${id}`, { tarea, descripcion, fecha });
        alert("Tarea actualizada con éxito.");
        this.cerrarModal();
        this.cargarTareas();
      } catch (error) {
        console.error("Error al actualizar la tarea:", error);
        alert("Hubo un error al actualizar la tarea.");
      }
    },
    async obtenerUbicacion() {
      try {
        const data = await GeolocationService.getLocation();
        this.location = data;
      } catch (error) {
        console.error("No se pudo obtener la ubicación:", error);
      }
    }
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
.btn-danger {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 4px;
  cursor: pointer;
}
.btn-danger:hover {
  background-color: #c82333;
}
.btn-warning {
  background-color: #ffc107;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 4px;
  cursor: pointer;
}
.btn-warning:hover {
  background-color: #e0a800;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}

.filters {
  margin-bottom: 20px;
  display: flex;
  gap: 20px;
  align-items: center;
}
.filter-group {
  display: flex;
  flex-direction: column;
}

.task-location {
  font-size: 0.9rem;
  color: #555;
  font-style: italic;
}
</style>
