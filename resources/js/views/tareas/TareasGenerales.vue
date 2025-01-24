<template>
  <div class="tareas-container">
    <h1 class="titulo">Tareas Finalizadas</h1>
    <div v-if="tareas.length === 0" class="no-tareas">No hay tareas finalizadas disponibles.</div>
    <div class="tabla-tareas">
      <div class="fila-tarea encabezado">
        <span class="columna">Título</span>
        <span class="columna">Descripción</span>
        <span class="columna">Usuario</span>
      </div>
      <div v-for="tarea in tareas" :key="tarea.id" class="fila-tarea">
        <span class="columna">{{ tarea.tarea }}</span>
        <span class="columna">{{ tarea.descripcion }}</span>
        <span class="columna">{{ tarea.user ? tarea.user.name : 'Sin asignar' }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      tareas: [], // Lista de tareas finalizadas
    };
  },
  created() {
    this.fetchTareasGenerales();
  },
  methods: {
    async fetchTareasGenerales() {
      try {
        const response = await axios.get("/api/tareas-generales");
        // Validamos que el formato esperado sea correcto
        if (response.data.success) {
          this.tareas = response.data.data.filter(
            (tarea) => tarea.estado && tarea.estado.estado === "finalizada"
          );
        } else {
          console.error("Error: Formato de respuesta inesperado");
        }
      } catch (error) {
        console.error("Error al obtener las tareas", error);
      }
    },
  },
};
</script>

<style scoped>
.tareas-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
  background: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.titulo {
  text-align: center;
  font-size: 2rem;
  color: #333;
  margin-bottom: 1.5rem;
}

.no-tareas {
  text-align: center;
  font-size: 1.2rem;
  color: #777;
}

.tabla-tareas {
  display: grid;
  gap: 1rem;
}

.fila-tarea {
  display: grid;
  grid-template-columns: 2fr 4fr 1fr;
  padding: 0.8rem;
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 5px;
  align-items: center;
}

.fila-tarea.encabezado {
  background: #333;
  color: #ffffff;
  font-weight: bold;
}

.columna {
  text-align: left;
  padding: 0 0.5rem;
}
</style>
