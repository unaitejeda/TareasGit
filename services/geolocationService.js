import axios from 'axios';

const API_KEY = '7936c5e9e6464133a07963f75fcb9ed2';
const BASE_URL = `https://api.ipgeolocation.io/ipgeo?apiKey=${API_KEY}`;

export default {
  getLocation() {
    return axios.get(BASE_URL)
      .then(response => {
        console.log('Geolocalización respuesta:', response.data);
        return response.data;
      })
      .catch(error => {
        console.error('Error al obtener la ubicación:', error);
        throw error;
      });
  }
};