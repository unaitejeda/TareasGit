<template>
  <div class="tareas-container">
    <h1 class="titulo">Tareas Finalizadas</h1>

    <div class="filtros">
      <input type="text" v-model="searchQuery" @input="guardarFiltros" placeholder="Buscar por título" />
      <select v-model="filtroInicialUsuario" @change="guardarFiltros">
        <option value="">Todas las iniciales</option>
        <option v-for="inicial in inicialesUsuarios" :key="inicial" :value="inicial">
          {{ inicial }}
        </option>
      </select>
    </div>

    <div v-if="tareasFiltradas.length === 0" class="no-tareas">
      No hay tareas finalizadas disponibles.
    </div>

    <div class="fila-tarea encabezado">
      <span class="columna">Título</span>
      <span class="columna">Descripción</span>
      <span class="columna">Usuario</span>
      <span class="columna">Ubicación</span>
    </div>
    <div v-for="tarea in tareasFiltradas" :key="tarea.id" class="fila-tarea">
      <span class="columna">{{ tarea.tarea }}</span>
      <span class="columna">{{ tarea.descripcion }}</span>
      <span class="columna">{{ tarea.user ? tarea.user.name : "Sin asignar" }}</span>
      <span class="columna">
        {{ tarea.ubicacion ? tarea.ubicacion : "Ubicación no disponible" }}
      </span>
    </div>

    <div class="resumen-tareas" v-if="tareas.length > 0">
      <p>
        Total de tareas finalizadas: <strong>{{ totalTareas }}</strong>
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      tareas: [],
      searchQuery: "",
      filtroInicialUsuario: "",
    };
  },
  computed: {
    tareasFiltradas() {
      return this.tareas
        .filter((tarea) => {
          const tituloIncluyeBusqueda = tarea.tarea
            .toLowerCase()
            .includes(this.searchQuery.toLowerCase());

          const inicialUsuario = tarea.user
            ? tarea.user.name[0].toUpperCase()
            : "";
          const cumpleInicial =
            !this.filtroInicialUsuario ||
            inicialUsuario === this.filtroInicialUsuario;

          return tituloIncluyeBusqueda && cumpleInicial;
        })
        .map((tarea) => ({
          ...tarea,
          descripcionResumida:
            tarea.descripcion.length > 50
              ? tarea.descripcion.slice(0, 50) + "..."
              : tarea.descripcion,
        }));
    },
    totalTareas() {
      return this.tareasFiltradas.reduce((total, tarea) => total + 1, 0);
    },
    inicialesUsuarios() {
      const iniciales = this.tareas
        .map((tarea) => (tarea.user ? tarea.user.name[0].toUpperCase() : ""))
        .filter((inicial) => inicial !== "");
      return [...new Set(iniciales)];
    },
  },
  created() {
    this.cargarFiltros();
    this.fetchTareasGenerales();
  },
  methods: {
    async fetchTareasGenerales() {
      try {
        const response = await axios.get("/api/tareas-generales");
        if (response.data.success) {
          this.tareas = response.data.data.filter(
            (tarea) => tarea.estado && tarea.estado.estado === "finalizada"
          );
        }
      } catch (error) {
        console.error("Error al obtener las tareas", error);
      }
    },
    guardarFiltros() {
      localStorage.setItem(
        "filtrosTareasGenerales",
        JSON.stringify({
          searchQuery: this.searchQuery,
          filtroInicialUsuario: this.filtroInicialUsuario,
        })
      );
    },
    cargarFiltros() {
      const filtrosGuardados = localStorage.getItem("filtrosTareasGenerales");
      if (filtrosGuardados) {
        const { searchQuery, filtroInicialUsuario } = JSON.parse(
          filtrosGuardados
        );
        this.searchQuery = searchQuery || "";
        this.filtroInicialUsuario = filtroInicialUsuario || "";
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

.filtros {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.filtros input,
.filtros select {
  padding: 0.5rem;
  font-size: 1rem;
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

.resumen-tareas {
  margin-top: 1.5rem;
  text-align: center;
}

.fila-tarea {
  display: grid;
  grid-template-columns: 2fr 4fr 1fr 2fr; /* Agregamos espacio para la ubicación */
  padding: 0.8rem;
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 5px;
  align-items: center;
}
</style>
