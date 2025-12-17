import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import 'bootstrap/dist/css/bootstrap.rtl.min.css';
import './styles.scss';

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');
