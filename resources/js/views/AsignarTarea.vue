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

<script>
import axios from "axios";
import GeolocationService from '/services/GeolocationService';

export default {
  data() {
    return {
      tarea: {
        tarea: "",
        descripcion: "",
        fecha: "",
        idlugar: null,
        idmomento: null,
        idtipo: null,
        ubicacion: "", 
        user_id: null, // Añadimos el campo para el usuario
      },
      lugares: [],
      momentos: [],
      tipos: [],
      usuarios: [], // Aquí almacenamos los usuarios
    };
  },
  created() {
    this.fetchData();
    this.fetchUsuarios(); // Traemos los usuarios
    this.getUbicacion();
  },
  methods: {
    async fetchData() {
      try {
        const [lugares, momentos, tipos] = await Promise.all([
          axios.get("/api/lugares"),
          axios.get("/api/momentodia"),
          axios.get("/api/tipos"),
        ]);
        this.lugares = lugares.data;
        this.momentos = momentos.data;
        this.tipos = tipos.data;
      } catch (error) {
        console.error("Error al obtener datos", error);
      }
    },
    async fetchUsuarios() {
      try {
        const response = await axios.get("/api/users"); // Cambiar la URL si es necesario
        this.usuarios = response.data;
      } catch (error) {
        console.error("Error al obtener usuarios", error);
      }
    },
    async getUbicacion() {
      try {
        const locationData = await GeolocationService.getLocation();
        const city = locationData.city || locationData.district || "Ubicación desconocida";
        const state = locationData.state_prov || "Provincia desconocida";
        const country = locationData.country_name || "País desconocido";

        this.tarea.ubicacion = `${city}, ${state}, ${country}`;
      } catch (error) {
        this.tarea.ubicacion = "Ubicación no disponible";
        console.warn("No se pudo obtener la ubicación", error);
      }
    },
    async crearTarea() {
      try {
        await axios.post("/api/tareas-asignadas", this.tarea); // Cambiar la URL si es necesario
        this.$router.push({ name: "tareas.generales" });
      } catch (error) {
        console.error("Error al asignar la tarea", error);
      }
    },
  },
};
</script>

<style scoped>
/* Aquí puedes añadir estilos similares a los de la otra vista */
</style>
