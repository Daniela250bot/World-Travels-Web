import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar la base URL para las peticiones API
window.axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
