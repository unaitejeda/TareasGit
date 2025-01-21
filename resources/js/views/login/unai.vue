<template>
    <form @submit.prevent="login">
      <input v-model="mail" type="email" placeholder="Correo electrónico" required />
      <input v-model="password" type="password" placeholder="Contraseña" required />
      <button type="submit">Iniciar Sesión</button>
    </form>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        mail: '',
        password: '',
      };
    },
    methods: {
      async login() {
        try {
          const response = await axios.post('/api/login', {
            mail: this.mail,
            password: this.password,
          });
          localStorage.setItem('token', response.data.token);
          alert('Inicio de sesión exitoso');
        } catch (error) {
          alert('Error en el inicio de sesión');
          console.error(error.response.data);
        }
      },
    },
  };
  </script>
  