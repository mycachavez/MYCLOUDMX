import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Login from './views/Login.vue'
import Dashboard from './views/Dashboard.vue'
import Home from './views/Home.vue'
import Caratulas from './views/Caratulas.vue'
import ReportesDomicilio from './views/ReportesDomicilio.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    {
      path: '/app',
      component: Dashboard,
      redirect: '/app/home',
      children: [
        { path: 'home',               component: Home },
        { path: 'caratulas',          component: Caratulas },
        { path: 'reportes-domicilio', component: ReportesDomicilio },
      ]
    },
  ]
})

createApp(App).use(router).mount('#app')