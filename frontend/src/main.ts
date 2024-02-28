import { createApp } from 'vue';
import App from './App.vue'
import './scss/default.scss';
import './scss/fonts.scss';
import router from './router';
import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'

const app = createApp(App)
app.use(router)
app.use(PerfectScrollbar)
app.mount('#app')