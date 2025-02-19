<template>
  <div class="formulario-container">
    <h1 class="titulo">Asignar Nueva Tarea</h1>
    <form @submit.prevent="crearTarea" class="formulario">
      <div class="campo">
        <label for="tarea">Título</label>
        <input v-model="tarea.tarea" type="text" id="tarea" required />
      </div>
      <div class="campo">
        <label for="descripcion">Descripción</label>
        <textarea v-model="tarea.descripcion" id="descripcion"></textarea>
      </div>
      <div class="campo">
        <label for="fecha">Fecha</label>
        <input v-model="tarea.fecha" type="date" id="fecha" required />
      </div>
      <div class="campo">
        <label for="usuario">Usuario</label>
        <select v-model="tarea.user_id" id="usuario" required>
          <option v-for="user in usuarios" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>
      <div class="campo">
        <label for="idlugar">Lugar</label>
        <select v-model="tarea.idlugar" id="idlugar">
          <option v-for="lugar in lugares" :key="lugar.id" :value="lugar.id">
            {{ lugar.lugar }}
          </option>
        </select>
      </div>
      <div class="campo">
        <label for="idmomento">Momento del día</label>
        <select v-model="tarea.idmomento" id="idmomento">
          <option v-for="momento in momentos" :key="momento.id" :value="momento.id">
            {{ momento.momento }}
          </option>
        </select>
      </div>
      <div class="campo">
        <label for="idtipo">Tipo</label>
        <select v-model="tarea.idtipo" id="idtipo">
          <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
            {{ tipo.tipo }}
          </option>
        </select>
      </div>
      <button type="submit" class="btn-crear">Asignar Tarea</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import GeolocationService from "/services/GeolocationService";
import { useRouter } from "vue-router";

const tarea = ref({
  tarea: "",
  descripcion: "",
  fecha: "",
  idlugar: null,
  idmomento: null,
  idtipo: null,
  ubicacion: "",
  user_id: null,
});

const lugares = ref([]);
const momentos = ref([]);
const tipos = ref([]);
const usuarios = ref([]);

const router = useRouter();

const fetchData = async () => {
  try {
    const [lugaresData, momentosData, tiposData] = await Promise.all([
      axios.get("/api/lugares"),
      axios.get("/api/momentodia"),
      axios.get("/api/tipos"),
    ]);
    lugares.value = lugaresData.data;
    momentos.value = momentosData.data;
    tipos.value = tiposData.data;
  } catch (error) {
    console.error("Error al obtener datos", error);
  }
};

const fetchUsuarios = async () => {
  try {
    const response = await axios.get("/api/users");
    usuarios.value = response.data;
  } catch (error) {
    console.error("Error al obtener usuarios", error);
  }
};

const getUbicacion = async () => {
  try {
    const locationData = await GeolocationService.getLocation();
    const city = locationData.city || locationData.district || "Ubicación desconocida";
    const state = locationData.state_prov || "Provincia desconocida";
    const country = locationData.country_name || "País desconocido";
    tarea.value.ubicacion = `${city}, ${state}, ${country}`;
  } catch (error) {
    tarea.value.ubicacion = "Ubicación no disponible";
    console.warn("No se pudo obtener la ubicación", error);
  }
};

const crearTarea = async () => {
  try {
    await axios.post("/api/tareas-asignadas", tarea.value);
    router.push({ name: "tareas.generales" });
  } catch (error) {
    console.error("Error al asignar la tarea", error);
  }
};

onMounted(() => {
  fetchData();
  fetchUsuarios();
  getUbicacion();
});
</script>

<style scoped>
.formulario-container {
  max-width: 600px;
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

.formulario {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.campo {
  display: flex;
  flex-direction: column;
}

.campo label {
  margin-bottom: 0.5rem;
  font-weight: bold;
}

.campo input,
.campo textarea,
.campo select {
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.btn-crear {
  padding: 0.8rem 1.5rem;
  background: #4caf50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  align-self: flex-start;
}

.btn-crear:hover {
  background: #43a047;
}
</style>
