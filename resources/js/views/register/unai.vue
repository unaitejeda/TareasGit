<template>
    <form @submit.prevent="register">
      <input v-model="nombre" type="text" placeholder="Nombre" required />
      <input v-model="apellido" type="text" placeholder="Apellido" required />
      <select v-model="sexo" required>
        <option value="" disabled>Seleccione su sexo</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
        <option value="Otro">Otro</option>
      </select>
      <input v-model="mail" type="email" placeholder="Correo electrónico" required />
      <input v-model="password" type="password" placeholder="Contraseña" required />
      <input v-model="password_confirmation" type="password" placeholder="Confirma tu contraseña" required />
      <button type="submit">Registrarse</button>
    </form>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        nombre: '',
        apellido: '',
        sexo: '',
        mail: '',
        password: '',
        password_confirmation: '',
      };
    },
    methods: {
      async register() {
        try {
          const response = await axios.post('/api/register', {
            nombre: this.nombre,
            apellido: this.apellido,
            sexo: this.sexo,
            mail: this.mail,
            password: this.password,
            password_confirmation: this.password_confirmation,
          });
          alert('Registro exitoso');
          console.log(response.data);
        } catch (error) {
          alert('Error en el registro');
          console.error(error.response.data);
        }
      },
    },
  };
  </script>
  