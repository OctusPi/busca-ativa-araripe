import 'bootstrap/dist/js/bootstrap.bundle.min'
import '@vuepic/vue-datepicker/dist/main.css'
import '@/assets/css/main.css'

import { createApp } from 'vue'
import { vMaska } from "maska"

import App from './App.vue'
import router from './router'
import VueDatePicker from '@vuepic/vue-datepicker';

const app = createApp(App)

app.use(router)
app.directive('maska', vMaska)
app.component('VueDatePicker', VueDatePicker);

app.provide('sysapp', {
    name: import.meta.env.VITE_APP_NAME ?? 'Merenda Escola',
    desc: import.meta.env.VITE_APP_DESC ?? 'Gestão e Controle de Contratos',
    copy: import.meta.env.VITE_APP_COPY ?? 'Depto. Tecnologia Araripe 2024 '
  })

app.mount('#app')
