import Vue from 'vue';
import axios from 'axios';
import { getToken } from '../auth';

if (getToken()) {
  axios.defaults.headers.common['Authorization'] = `bearer ${getToken()}`;
}

Vue.prototype.$axios = axios;
