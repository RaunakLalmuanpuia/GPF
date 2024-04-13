import './bootstrap';
import "../css/app.css";

import { createApp } from 'vue';
import app from './components/app.vue';
import { Quasar,  Notify, Loading,Dialog } from 'quasar'
import router from './router/index.js';

// Import icon libraries
import "@quasar/extras/roboto-font/roboto-font.css";
import "@quasar/extras/material-icons/material-icons.css";

// Import Quasar css
import "quasar/src/css/index.sass";

createApp(app).use(router).use(Quasar, {
  plugins:[Notify,Loading,Dialog], // import Quasar plugins and add here
  }).mount('#app')