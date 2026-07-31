import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Login from './views/Login.vue'
import Dashboard from './views/Dashboard.vue'
import Home from './views/Home.vue'
import Caratulas from './views/Caratulas.vue'
import ReportesDomicilio from './views/ReportesDomicilio.vue'
import { authStore } from './stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    {
      path: '/app',
      component: Dashboard,
      redirect: '/app/home',
      meta: { requiresAuth: true },
      children: [
        { path: 'home',               component: Home },
        { path: 'caratulas',          component: Caratulas },
        { path: 'reportes-domicilio', component: ReportesDomicilio },
      ]
    },
  ]
})

router.beforeEach(async (to) => {
  if (!authStore.state.checked) {
    await authStore.fetchBanco()
  }

  console.log('¿Autenticado?', authStore.isAuthenticated, authStore.state.banco) // <-- temporal

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return { path: '/login' }
  }

  if (to.path === '/login' && authStore.isAuthenticated) {
    return { path: '/app/home' }
  }
})

createApp(App).use(router).mount('#app')