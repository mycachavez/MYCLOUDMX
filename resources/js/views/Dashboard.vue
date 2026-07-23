<template>
  <div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'sidebar--collapsed': collapsed }">

      <div class="sidebar-header">
        <div class="brand">
          <div class="brand-icon">
            <img :src="logo" width="28" />
          </div>
          <span class="brand-name" v-show="!collapsed">AHMEX</span>
        </div>
        <button class="collapse-btn" @click="collapsed = !collapsed" :aria-label="collapsed ? 'Expandir menú' : 'Colapsar menú'">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path v-if="!collapsed" d="M15 18l-6-6 6-6"/>
            <path v-else d="M9 18l6-6-6-6"/>
          </svg>
        </button>
      </div>

      <nav class="sidebar-nav">
        <button
          v-for="item in menuItems"
          :key="item.id"
          class="nav-item"
          :class="{ 'nav-item--active': route.path === item.path }"
          @click="navigate(item)"
          :aria-label="item.label"
        >
          <span class="nav-icon" v-html="item.icon"></span>
          <span class="nav-label" v-show="!collapsed">{{ item.label }}</span>
        </button>
      </nav>

      <div class="sidebar-footer">
        <div class="user-info" v-show="!collapsed">
          <div class="user-avatar">ES</div>
          <div class="user-details">
            <span class="user-name">Esteban C.</span>
            <span class="user-role">Administrador</span>
          </div>
        </div>
        <div class="user-avatar user-avatar--sm" v-show="collapsed">ES</div>
        <button class="logout-btn" @click="handleLogout" aria-label="Cerrar sesión">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </button>
      </div>

    </aside>

    <!-- Contenido principal -->
    <main class="main-content">

      <header class="topbar">
        <div class="topbar-left">
          <h1 class="page-title">{{ currentItem.label }}</h1>
          <span class="page-breadcrumb">Inicio / {{ currentItem.label }}</span>
        </div>
        <div class="topbar-right">
          <button class="topbar-btn" aria-label="Notificaciones">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="notif-dot"></span>
          </button>
        </div>
      </header>

      <div class="content-area">
         <router-view />
        <!-- <component :is="currentItem.component" /> -->
      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Home from './Home.vue'
import Caratulas from './Caratulas.vue'
import ReportesDomicilio from './ReportesDomicilio.vue'
import logo from '@/assets/images/logo.png'

const router = useRouter()
const route  = useRoute()

const collapsed = ref(false)

const menuItems = [
  {
    id: 1,
    path: '/app/home',
    label: 'Home',
    component: Home,
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
      <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
    </svg>`
  },
  {
    id: 2,
    path: '/app/caratulas',
    label: 'Carátulas',
    component: Caratulas,
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
      <polyline points="14 2 14 8 20 8"/>
    </svg>`
  },
  {
    id: 3,
    path: '/app/reportes-domicilio',
    label: 'Reportes de domicilio',
    component: ReportesDomicilio,
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="8" r="4"/>
      <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
    </svg>`
  }
]

const currentItem = computed(() => menuItems.find(i => i.path === route.path) ?? menuItems[0])

function navigate(item) {
  router.push(item.path)
}

function handleLogout() {
  window.location.href = '/login'
}
</script>