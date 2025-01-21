<template>
  <div>
    <h1>Crear nueva tarea</h1>
    <form @submit.prevent="submitTarea">
      <div>
        <label for="tarea">Título</label>
        <input v-model="tarea.tarea" type="text" id="tarea" required />
      </div>
      <div>
        <label for="descripcion">Descripción</label>
        <textarea v-model="tarea.descripcion" id="descripcion"></textarea>
      </div>
      <div>
        <label for="fecha">Fecha</label>
        <input v-model="tarea.fecha" type="date" id="fecha" required />
      </div>
      <div>
        <label for="idlugar">Lugar</label>
        <select v-model="tarea.idlugar" id="idlugar">
          <option v-for="lugar in lugares" :key="lugar.id" :value="lugar.id">{{ lugar.lugar }}</option>
        </select>
      </div>
      <div>
        <label for="idmomento">Momento del día</label>
        <select v-model="tarea.idmomento" id="idmomento">
          <option v-for="momento in momentos" :key="momento.id" :value="momento.id">{{ momento.momento }}</option>
        </select>
      </div>
      <div>
        <label for="idtipo">Tipo</label>
        <select v-model="tarea.idtipo" id="idtipo">
          <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">{{ tipo.tipo }}</option>
        </select>
      </div>
      <div>
        <label for="idestado">Estado</label>
        <select v-model="tarea.idestado" id="idestado">
          <option v-for="estado in estados" :key="estado.id" :value="estado.id">{{ estado.estado }}</option>
        </select>
      </div>
      <button type="submit">Crear Tarea</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tarea: {
        tarea: '',
        descripcion: '',
        fecha: '',
        idlugar: null,
        idmomento: null,
        idtipo: null,
        idestado: null,
      },
      lugares: [],
      momentos: [],
      tipos: [],
      estados: [],
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const [lugares, momentos, tipos, estados] = await Promise.all([
          axios.get('/api/lugares'),
          axios.get('/api/momentodia'),
          axios.get('/api/tipos'),
          axios.get('/api/estados'),
        ]);
        this.lugares = lugares.data;
        this.momentos = momentos.data;
        this.tipos = tipos.data;
        this.estados = estados.data;
      } catch (error) {
        console.error('Error al obtener datos', error);
      }
    },
    async submitTarea() {
      try {
        await axios.post('/api/tareas', this.tarea);
        this.$router.push({ name: 'tareas.generales' });
      } catch (error) {
        console.error('Error al crear la tarea', error);
      }
    },
  },
};
</script>
