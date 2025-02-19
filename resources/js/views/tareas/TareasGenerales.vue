<template>
  <div class="tareas-container">
    <h1 class="titulo">Tareas Finalizadas</h1>

    <div class="filtros">
      <InputText v-model="searchQuery" @input="guardarFiltros" placeholder="Buscar por título" class="p-inputtext-lg" />

      <Dropdown v-model="filtroInicialUsuario" :options="inicialesUsuarios"
        placeholder="Filtrar por inicial" class="p-dropdown-lg" @change="guardarFiltros">
        <template #value="slotProps">
          <span v-if="slotProps.value">{{ slotProps.value }}</span>
          <span v-else>Todas</span>
        </template>
      </Dropdown>
    </div>

    <Message v-if="tareasFiltradas.length === 0" severity="info">
      No hay tareas finalizadas disponibles.
    </Message>

    <DataTable :value="tareasFiltradas" responsiveLayout="scroll" class="p-datatable-sm">
      <Column field="tarea" header="Título"></Column>
      <Column field="descripcion" header="Descripción"></Column>
      <Column field="user.name" header="Usuario">
        <template #body="slotProps">
          {{ slotProps.data.user ? slotProps.data.user.name : "Sin asignar" }}
        </template>
      </Column>
      <Column field="ubicacion" header="Ubicación">
        <template #body="slotProps">
          {{ slotProps.data.ubicacion ? slotProps.data.ubicacion : "Ubicación no disponible" }}
        </template>
      </Column>
    </DataTable>

    <div class="resumen-tareas" v-if="tareas.length > 0">
      <p>
        Total de tareas finalizadas: <strong>{{ totalTareas }}</strong>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Message from "primevue/message";

const tareas = ref([]);
const searchQuery = ref("");
const filtroInicialUsuario = ref(null);

const fetchTareasGenerales = async () => {
  try {
    const response = await axios.get("/api/tareas-generales");
    if (response.data.success) {
      tareas.value = response.data.data.filter(
        (tarea) => tarea.estado && tarea.estado.estado === "finalizada"
      );
    }
  } catch (error) {
    console.error("Error al obtener las tareas", error);
  }
};

const guardarFiltros = () => {
  localStorage.setItem(
    "filtrosTareasGenerales",
    JSON.stringify({
      searchQuery: searchQuery.value,
      filtroInicialUsuario: filtroInicialUsuario.value,
    })
  );
};

const cargarFiltros = () => {
  const filtrosGuardados = localStorage.getItem("filtrosTareasGenerales");
  if (filtrosGuardados) {
    const { searchQuery: query, filtroInicialUsuario: inicial } = JSON.parse(filtrosGuardados);
    searchQuery.value = query || "";
    filtroInicialUsuario.value = inicial || null;
  }
};

const tareasFiltradas = computed(() => {
  return tareas.value.filter((tarea) => {
    const tituloIncluyeBusqueda = tarea.tarea.toLowerCase().includes(searchQuery.value.toLowerCase());
    const inicialUsuario = tarea.user ? tarea.user.name[0].toUpperCase() : "";
    const cumpleInicial = !filtroInicialUsuario.value || filtroInicialUsuario.value === "Todos" || inicialUsuario === filtroInicialUsuario.value;
    return tituloIncluyeBusqueda && cumpleInicial;
  });
});

const totalTareas = computed(() => tareasFiltradas.value.length);

const inicialesUsuarios = computed(() => {
  const iniciales = tareas.value
    .map((tarea) => (tarea.user ? tarea.user.name[0].toUpperCase() : ""))
    .filter((inicial) => inicial !== "");
  return ["Todos", ...new Set(iniciales)];
});

onMounted(() => {
  cargarFiltros();
  fetchTareasGenerales();
});
</script>

<style scoped>
.tareas-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
  background: #ffffff;
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
  gap: 10px;
}

.resumen-tareas {
  margin-top: 1.5rem;
  text-align: center;
}
</style>
