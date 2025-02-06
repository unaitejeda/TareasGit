// src/services/GeolocationService.js

import axios from 'axios';

const API_KEY = '7936c5e9e6464133a07963f75fcb9ed2'; // Reemplaza con tu clave API
const BASE_URL = `https://api.ipgeolocation.io/ipgeo?apiKey=${API_KEY}`;

export default {
  getLocation() {
    return axios.get(BASE_URL)
      .then(response => {
        // Se puede revisar la estructura de la respuesta para asegurarse de que estamos obteniendo la ciudad
        console.log('Geolocalización respuesta:', response.data);
        return response.data;
      })
      .catch(error => {
        console.error('Error al obtener la ubicación:', error);
        throw error;
      });
  }
};
