<template>
  <div class="login-wrapper">
    <div class="login-card">

      <div class="login-brand">
        <div class="brand-icon">
            <img :src="logo" width="150" />
        </div>
      </div>

      <h1 class="login-title">Bienvenido</h1>
      <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

      <form @submit.prevent="handleLogin" class="login-form">

        <div class="field-group">
          <label for="email" class="field-label">Correo electrónico</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="field-input"
            :class="{ 'field-input--error': errors.email }"
            placeholder="correo@ejemplo.com"
            autocomplete="email"
          />
          <span v-if="errors.email" class="field-error">{{ errors.email }}</span>
        </div>

        <div class="field-group">
          <label for="password" class="field-label">Contraseña</label>
          <div class="field-input-wrap">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              class="field-input field-input--password"
              :class="{ 'field-input--error': errors.password }"
              placeholder="••••••••"
              autocomplete="current-password"
            />
            <button type="button" class="toggle-password" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'">
              <svg v-if="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <span v-if="errors.password" class="field-error">{{ errors.password }}</span>
        </div>

        <div class="login-options">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.remember" class="checkbox-input" />
            <span class="checkbox-text">Recordarme</span>
          </label>
          <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>

        <div v-if="errorGeneral" class="alert-error">
          {{ errorGeneral }}
        </div>

        <button type="submit" class="btn-login" :disabled="loading">
          <span v-if="!loading">Iniciar sesión</span>
          <span v-else class="btn-loading">
            <svg class="spin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
            </svg>
            Verificando...
          </span>
        </button>

      </form>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import logo from '@/assets/images/logo.png'

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = reactive({
  email: '',
  password: ''
})

const errorGeneral = ref('')
const loading = ref(false)
const showPassword = ref(false)

function validate() {
  errors.email = ''
  errors.password = ''
  let valid = true

  if (!form.email) {
    errors.email = 'El correo es obligatorio.'
    valid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Ingresa un correo válido.'
    valid = false
  }

  if (!form.password) {
    errors.password = 'La contraseña es obligatoria.'
    valid = false
  } else if (form.password.length < 6) {
    errors.password = 'Mínimo 6 caracteres.'
    valid = false
  }

  return valid
}

async function handleLogin() {
  if (!validate()) return

  loading.value = true
  errorGeneral.value = ''

  try {
    const response = await fetch('/autenticar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
      },
      body: JSON.stringify({
        email: form.email,
        password: form.password,
        remember: form.remember
      })
    })

    const data = await response.json()

    if (!response.ok) {
      errorGeneral.value = data.message || 'Credenciales incorrectas.'
    } else {
      window.location.href = '/app/home'
    }
  } catch (e) {
    errorGeneral.value = 'Error de conexión. Intenta de nuevo.'
  } finally {
    loading.value = false
  }
}
</script>