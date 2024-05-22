import './assets/css/main.css'
import 'bootstrap/dist/js/bootstrap.bundle.min'
import '@vuepic/vue-datepicker/dist/main.css'
import { createApp } from 'vue'
import { vMaska } from "maska"
import App from './App.vue'
import router from './router'
import VueDatePicker from '@vuepic/vue-datepicker';

const app   = createApp(App)

app.use(router)
app.directive('maska', vMaska)
app.component('VueDatePicker', VueDatePicker);
app.provide('app', {
    name: import.meta.env.VITE_APP_NAME ?? 'Educa Araripe',
    desc: import.meta.env.VITE_APP_DESC ?? 'Sistema de Busca Ativa Escolar',
    copy: import.meta.env.VITE_APP_COPY ?? 'Dept. Tecnologia 2024'
})
app.mount('#app')
