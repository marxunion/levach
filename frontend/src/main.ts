import { createApp } from 'vue';

import App from './App.vue'
import router from './router';

import VueDatePicker from '@vuepic/vue-datepicker';
import PerfectScrollbar from 'vue3-perfect-scrollbar'

import './scss/default.scss';
import './scss/fonts.scss'

import '@vuepic/vue-datepicker/dist/main.css'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'

const app = createApp(App)

app.component('VueDatePicker', VueDatePicker);

app.use(router)
app.use(PerfectScrollbar)
app.mount('#app')