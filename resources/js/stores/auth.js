import { reactive } from 'vue'
import axios from '../bootstrap'

const state = reactive({
  banco: null,     // null = no autenticado / aún no se sabe
  checked: false,  // ya se intentó verificar la sesión al menos una vez
})

export const authStore = {
  state,

  get isAuthenticated() {
    return !!state.banco
  },

  async login(credentials) {
    const { data } = await axios.post('/autenticar', credentials)
    state.banco = data
    state.checked = true
    return data
  },

  async fetchBanco() {
    try {
      const { data } = await axios.get('/api/banco')
      state.banco = data
    } catch {
      state.banco = null
    } finally {
      state.checked = true
    }
  },

  async logout() {
    await axios.post('/logout')
    state.banco = null
  },
}