require('./bootstrap');

import {createApp, onMounted} from 'vue'
import router from './routes/index'
import VueSweetalert2 from 'vue-sweetalert2';
import useAuth from "./composables/auth";

const app = createApp({
    setup() {
        const { getUser } = useAuth()
        onMounted(getUser)
    }
})
app.use(VueSweetalert2);
app.use(router)
app.mount('#app')