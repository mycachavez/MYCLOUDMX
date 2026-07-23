<template>
  <div>
    <div class="content-card">
      <div class="card-header">
        <span class="card-title">Todas las carátulas</span>
      </div>

      <!-- Filtros -->
      <div class="table-filters">
         <template
            v-for="col in columns" 
            :key="col.key"
         >
            <div 
               class="filter-group"             
               v-if="col.filterable !== false"
            >
               <label class="filter-label">{{ col.label }}</label>
               <input
                  v-if="col.key !== 'estado'"
                  v-model="filters[col.key]"
                  class="filter-input"
                  :placeholder="'Filtrar...'"
                  type="text"
               />
               <select v-else v-model="filters.estado" class="filter-input filter-select">
                  <option value="">Todos</option>
                  <option v-for="opt in estadoOpts" :key="opt" :value="opt">{{ opt }}</option>
               </select>
            </div>
         </template>
         
         <div class="filter-group">
            <label class="filter-label">Fecha desde</label>
            <VueDatePicker
               v-model="filters.fechaDesde"
               :enable-time-picker="false"
               format="yyyy-MM-dd"
               locale="es"
               placeholder="Desde..."
               :max-date="filters.fechaHasta || null"
               auto-apply
               input-class-name="filter-input"
            />
         </div>
         <div class="filter-group">
            <label class="filter-label">Fecha hasta</label>
            <VueDatePicker
               v-model="filters.fechaHasta"
               :enable-time-picker="false"
               format="yyyy-MM-dd"
               locale="es"
               placeholder="Hasta..."
               :min-date="filters.fechaDesde || null"
               auto-apply
               input-class-name="filter-input"
            />
         </div>
         <div class="filter-group filter-group--clear">
            <label class="filter-label">&nbsp;</label>
            <div style="display: flex; gap: 8px;">
               
               <button class="btn-clear" @click="clearFilters" :disabled="noFilters">
               <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                  <path d="M18 6L6 18M6 6l12 12"/>
               </svg>
               Limpiar
               </button>

               <button class="btn-clear" @click="aplicarFiltro" :disabled="loading"> <!-- 👈 -->
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                     <path d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                  </svg>
                  Aplicar filtro
               </button>

            </div>
         </div>
      </div>

      <!-- Tabla -->
      <div class="table-wrap">
         <table class="data-table">
            <thead>
               <tr>
               <th v-for="col in columns" :key="col.key" @click="setSort(col.key)" class="th-sortable">
                  {{ col.label }}
                  <span class="sort-icon">
                     <svg v-if="sort.key === col.key && sort.dir === 'asc'" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                     <svg v-else-if="sort.key === col.key && sort.dir === 'desc'" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 19V5M5 12l7 7 7-7"/></svg>
                     <svg v-else width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.3"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                  </span>
               </th>
               </tr>
            </thead>
            <tbody>
               <tr v-if="loading">
               <td :colspan="columns.length" class="td-empty">
                  <div style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 2rem;">
                     <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="animation: spin 1s linear infinite;">
                     <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                     </svg>
                     Cargando...
                  </div>
               </td>
               </tr>
               <tr v-if="filteredRows.length === 0 && !loading">
                  <td :colspan="columns.length" class="td-empty">Sin resultados</td>
               </tr>
               <tr 
                  v-if="!loading"
                  v-for="row in filteredRows" 
                  :key="row.Deal_Name" 
                  class="tr-row"
                  @click="abrirDetalle(row)"
                  style="cursor: pointer;"
               >
                  <td class="td-folio">{{ row.Deal_Name }}</td>
                  <td>{{ row.Nombre_s }}</td>
                  <td>{{ row.Apellido_paterno }}</td>
                  <td>{{ row.Apellido_materno }}</td>
                  <td>
                     {{ row.Contact_Name?.name }}
                  </td>
                  <td>
                     {{ row.Account_Name?.name }}
                  </td>
                  <td>{{ row.RFC }}</td>
                  <td class="td-importe">{{ formatImporte(row.Amount) }}</td>
                  <td>{{ row.Created_Time }}</td>
               </tr>
            </tbody>
         </table>
      </div>

      <!-- Footer -->
      <div class="table-footer">
         <div style="display: flex; align-items: center; gap: 8px;">
            <button class="btn-clear" @click="prevPage" :disabled="currentPage === 1 || loading">
               &laquo; Anterior
            </button>
            <button class="btn-clear" @click="nextPage" :disabled="!hasNext || loading">
               Siguiente &raquo;
            </button>
         </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
 
const columns = [
   { key: 'Deal_Name',    label: 'Folio',    filterable: true },
   { key: 'Nombre_s',   label: 'Nombre',    filterable: true },
   { key: 'Apellido_paterno', label: 'Apellido Paterno',    filterable: true  },
   { key: 'Apellido_materno', label: 'Apellido Materno',    filterable: true  },
   { key: 'Contact_Name',   label: 'Asesor',    filterable: true  },
   { key: 'Account_Name',    label: 'Master broker', filterable: true  },
   { key: 'RFC',    label: 'RFC',    filterable: true     },
   { key: 'Amount',  label: 'Importe',    filterable: true   },
   { key: 'Created_Time',      label: 'Fecha creación', filterable: false }
]
 
const currentPage = ref(1)
const perPage = 100
const hasNext = ref(false)

 
const rows    = ref([])
const loading = ref(false)
const error   = ref('')
 
async function fetchData(page = 1) {
   loading.value = true
   error.value   = ''
   try {
      
      const filtersToSend = Object.fromEntries(
         Object.entries(activeFilters.value)
            .filter(([_, v]) => v !== '' && v !== null)
            .map(([k, v]) => {
               if ((k === 'fechaDesde' || k === 'fechaHasta') && v instanceof Date) {
                  return [k, v.toISOString().slice(0, 10)] // 👈 yyyy-mm-dd
               }
               return [k, v]
            })
      )

      const params = new URLSearchParams({
         page,
         per_page: perPage,
         ...filtersToSend
      })
      const res = await fetch(`/getCaratulas?${params}`, {
         headers: { 'Accept': 'application/json' }
      })
      if (!res.ok) throw new Error(`Error ${res.status}: ${res.statusText}`)
      const json = await res.json()
      rows.value = json.data ?? json

      hasNext.value = json.info?.more_records ?? false 
   } catch (e) {
      error.value = e.message || 'No se pudo conectar con el servidor.'
   } finally {
      loading.value = false
   }
}

function prevPage() {
  if (currentPage.value === 1) return
  currentPage.value--
  fetchData(currentPage.value)
}

function nextPage() {
  if (!hasNext.value) return
  currentPage.value++
  fetchData(currentPage.value)
}
 
onMounted(() => fetchData(1))

const filters = ref({ Deal_Name: '', Nombre_s: '', Apellido_paterno: '', Apellido_materno: '', Contact_Name: '', Account_Name:'', RFC: '', Amount: ' ' })
const activeFilters = ref({ Deal_Name: '', Nombre_s: '', Apellido_paterno: '', Apellido_materno: '', Contact_Name: '', Account_Name:'', RFC: '', Amount: '' })

const sort    = ref({ key: 'folio', dir: 'asc' })

const noFilters = computed(() => Object.values(filters.value).every(v => v === ''))
 
function clearFilters() {
   Object.keys(filters.value).forEach(k => filters.value[k] = '')
   Object.keys(activeFilters.value).forEach(k => activeFilters.value[k] = '')
   currentPage.value = 1
   fetchData(1)
}
 
function setSort(key) {
   if (sort.value.key === key) {
      sort.value.dir = sort.value.dir === 'asc' ? 'desc' : 'asc'
   } else {
      sort.value.key = key
      sort.value.dir = 'asc'
   }
}
 
const filteredRows = computed(() => {
   let result = rows.value.filter(row => {
      return columns.every(col => {
         const f = activeFilters.value[col.key]?.toString().toLowerCase().trim()
         //const f = filters.value[col.key]?.toString().toLowerCase().trim()
         if (!f) return true

         if (col.key === 'Contact_Name') {
         return row.Contact_Name?.name?.toLowerCase().includes(f)
         }

         if (col.key === 'Account_Name') {
         return row.Account_Name?.name?.toLowerCase().includes(f)
         }
         
         const val = col.key === 'importe'
         ? formatImporte(row[col.key]).toLowerCase()
         : row[col.key]?.toString().toLowerCase()
         return val.includes(f)
      })
   })
 
   return [...result].sort((a, b) => {
      const key = sort.value.key
      let va = a[key], vb = b[key]
      if (typeof va === 'number') return sort.value.dir === 'asc' ? va - vb : vb - va
      return sort.value.dir === 'asc'
      ? va?.toString().localeCompare(vb?.toString(), 'es')
      : vb?.toString().localeCompare(va?.toString(), 'es')
   })
})

const abrirDetalle = (row) => {
    window.open(
      `https://servicios.mycloud.mx/ahmex/caratula/generar/${row.id}`, 
      '_blank',
      'width=800,height=600,top=100,left=100'
   )
}

function aplicarFiltro() {
   activeFilters.value = { ...filters.value }
   currentPage.value = 1
   fetchData(1)
}
 
function formatImporte(val) {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val)
}
 
</script>