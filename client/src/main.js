import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import axios from "axios";

window.axios = axios;

createApp(App).mount('#app')
