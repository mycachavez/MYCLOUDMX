<template>
  <div>
    <div class="stat-grid">
      <div class="stat-card" v-for="stat in stats" :key="stat.label">
        <span class="stat-label">{{ stat.label }}</span>
        <span class="stat-value">{{ stat.value }}</span>
        <span class="stat-change" :class="stat.up ? 'stat-change--up' : 'stat-change--down'">
          {{ stat.up ? '↑' : '↓' }} {{ stat.delta }}
        </span>
      </div>
    </div>
    <div class="content-card">
      <div class="card-header">
        <span class="card-title">Resumen general</span>
      </div>
      <div class="empty-state">
        <div class="empty-icon">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
        </div>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const stats = [
  { label: 'Total Carátulas',       value: '1,284', delta: '12%', up: true  },
  { label: 'Total Reportes Domicilio',     value: '842',   delta: '5%',  up: true  },
  { label: 'Pendientes',  value: '97',    delta: '3%',  up: false },
  { label: 'Completados', value: '345',   delta: '8%',  up: true  },
]
</script>