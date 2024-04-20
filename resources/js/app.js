import './bootstrap';
import '../css/app.css';
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/material-icons-outlined/material-icons-outlined.css' 
import 'quasar/src/css/index.sass'


import { createApp } from 'vue';
import app from './components/app.vue';
import { Quasar,  Notify, Loading,Dialog } from 'quasar'
import router from './router/index.js';



createApp(app).use(router).use(Quasar, {
  plugins:[Notify,Loading,Dialog], // import Quasar plugins and add here
  }).mount('#app')