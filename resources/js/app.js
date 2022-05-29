require('./bootstrap');

import { createApp } from 'vue'
import App from './layouts/App'
import router from './routes/index'
import VueSweetalert2 from 'vue-sweetalert2';

const app = createApp(App)
app.use(VueSweetalert2);
app.use(router)
app.mount('#app')