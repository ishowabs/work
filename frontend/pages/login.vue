<template>
  <div class="supabase-app">
    <v-container fluid class="supabase-container pa-0" style="height: 100vh;">
      <div class="supabase-content">
        <v-card width="400" class="supabase-card" elevation="0">
          <!-- Header Section -->
          <div class="supabase-header">
            <div class="supabase-logo">
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21.362 9.354H12V.396a.396.396 0 0 0-.716-.233L2.203 12.424l-.401.562a1.04 1.04 0 0 0 0 1.028l.401.562 9.081 12.261a.396.396 0 0 0 .716-.233V17.646h9.362c.818 0 1.171-.645.51-1.182l-9.7-7.909a.396.396 0 0 1 0-.618l9.7-7.909c.661-.537.308-1.182-.51-1.182Z"/>
              </svg>
            </div>
            <h1 class="supabase-title">{{ t('welcome') }}</h1>
            <p class="supabase-subtitle">{{ t('login') }}</p>
          </div>

          <v-card-text class="supabase-card-content">
            <v-form @submit.prevent="onSubmit" ref="formRef" v-model="valid">
              <div class="supabase-field">
                <label class="supabase-label">{{ t('email') }}</label>
                <v-text-field
                  v-model="email"
                  type="email"
                  :rules="[v => !!v || t('email_required')]"
                  required
                  variant="outlined"
                  class="supabase-input"
                  placeholder="you@example.com"
                  hide-details="auto"
                  density="comfortable"
                />
              </div>
              
              <div class="supabase-field">
                <label class="supabase-label">{{ t('password') }}</label>
                <v-text-field
                  v-model="password"
                  type="password"
                  :rules="[v => !!v || t('password_required')]"
                  required
                  variant="outlined"
                  class="supabase-input"
                  placeholder="Your password"
                  hide-details="auto"
                  density="comfortable"
                />
              </div>

              <!-- Forgot Password Link -->
              <div class="supabase-forgot">
                <a href="#" class="supabase-link">{{ t('forgot_password') }}</a>
              </div>

              <v-btn 
                :loading="loading" 
                type="submit" 
                block
                class="supabase-btn"
                :disabled="loading"
              >
                <span v-if="loading">{{ t('logging_in') }}...</span>
                <span v-else>{{ t('login') }}</span>
              </v-btn>

              <v-alert 
                v-if="error" 
                class="supabase-alert"
                variant="tonal"
                type="error"
              >
                {{ error }}
              </v-alert>
            </v-form>

            <div class="supabase-signup">
              <span class="supabase-signup-text">{{ t('register') }}</span>
              <button @click="goToRegister" class="supabase-signup-link">
                {{ t('register') }}
              </button>
            </div>
          </v-card-text>
        </v-card>
      </div>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useRuntimeConfig } from 'nuxt/app'

const router = useRouter()
const { t } = useI18n()
const config = useRuntimeConfig()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const valid = ref(true)
const formRef = ref()

const onSubmit = async () => {
  error.value = ''
  if (!formRef.value?.validate()) return

  loading.value = true
  try {
    const response = await fetch(`${config.public.apiUrl}/api/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })
    const data = await response.json()
    if (response.ok && typeof data.token === 'string') {
      // Store token in a reactive state instead of localStorage
      // You might want to use a composable or store for this
      if (process.client) {
        localStorage.setItem('jwt', data.token)
      }
      await fetchUser()
      router.push('/dashboard')
    } else {
      error.value = data.error || 'Login failed'
    }
  } catch (e) {
    error.value = 'Login failed'
  } finally {
    loading.value = false
  }
}

function goToRegister() {
  router.push('/register')
}

async function fetchUser() {
  if (!process.client) return
  
  const token = localStorage.getItem('jwt')
  if (!token) {
    console.error('No JWT token found in localStorage')
    return
  }
  
  try {
    const res = await fetch(`${config.public.apiUrl}/api/me`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    const text = await res.text()
    if (!res.ok) {
      throw new Error('Failed to fetch user: ' + text)
    }
    const user = JSON.parse(text)
    console.log('User fetched:', user)
  } catch (error) {
    console.error('Error fetching user:', error)
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif !important;
}

body, html {
  margin: 0 !important;
  padding: 0 !important;
  overflow-x: hidden !important;
}

.v-application {
  background: #0c0c0c !important;
}
</style>

<style scoped>
.supabase-app {
  background: #0c0c0c;
  min-height: 100vh;
  width: 100vw;
  margin: 0;
  padding: 0;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.supabase-container {
  background: #0c0c0c !important;
  min-height: 100vh !important;
  width: 100% !important;
  margin: 0 !important;
  padding: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

.supabase-content {
  width: 100%;
  max-width: 400px;
  padding: 20px;
}

.supabase-card {
  background: #1c1c1c !important;
  border: 1px solid #2a2a2a !important;
  border-radius: 12px !important;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
}

.supabase-header {
  text-align: center;
  padding: 40px 32px 24px;
  background: #1c1c1c;
}

.supabase-logo {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #3ecf8e 0%, #2dd4bf 100%);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  color: #000;
}

.supabase-title {
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
  margin: 0 0 8px 0;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.supabase-subtitle {
  color: #9ca3af;
  margin: 0;
  font-size: 15px;
  font-weight: 400;
  line-height: 1.4;
}

.supabase-card-content {
  padding: 0 32px 40px !important;
  background: #1c1c1c !important;
}

.supabase-field {
  margin-bottom: 20px;
}

.supabase-label {
  display: block;
  color: #f3f4f6;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 8px;
  line-height: 1.4;
}

.supabase-input :deep(.v-field) {
  background: #262626 !important;
  border-radius: 8px !important;
  border: 1px solid #404040 !important;
  min-height: 48px !important;
}

.supabase-input :deep(.v-field__input) {
  color: #ffffff !important;
  font-size: 14px !important;
  font-weight: 400 !important;
  padding: 12px 16px !important;
}

.supabase-input :deep(.v-field__input::placeholder) {
  color: #6b7280 !important;
  opacity: 1 !important;
}

.supabase-input :deep(.v-field__outline) {
  display: none !important;
}

.supabase-input :deep(.v-field--focused) {
  border-color: #3ecf8e !important;
  box-shadow: 0 0 0 3px rgba(62, 207, 142, 0.1) !important;
}

.supabase-forgot {
  text-align: right;
  margin: 16px 0 24px;
}

.supabase-link {
  color: #3ecf8e;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: color 0.2s ease;
}

.supabase-link:hover {
  color: #2dd4bf;
}

.supabase-btn {
  background: linear-gradient(135deg, #3ecf8e 0%, #2dd4bf 100%) !important;
  color: #000000 !important;
  border-radius: 8px !important;
  text-transform: none !important;
  font-weight: 600 !important;
  height: 48px !important;
  font-size: 14px !important;
  letter-spacing: 0.01em !important;
  box-shadow: 0 2px 8px rgba(62, 207, 142, 0.2) !important;
  transition: all 0.2s ease !important;
}

.supabase-btn:hover {
  background: linear-gradient(135deg, #2dd4bf 0%, #14b8a6 100%) !important;
  box-shadow: 0 4px 12px rgba(62, 207, 142, 0.3) !important;
  transform: translateY(-1px) !important;
}

.supabase-btn:disabled {
  opacity: 0.6 !important;
  transform: none !important;
}

.supabase-alert {
  background: #2d1b1b !important;
  border: 1px solid #5c2626 !important;
  border-radius: 8px !important;
  margin-top: 16px !important;
  color: #fca5a5 !important;
}

.supabase-signup {
  text-align: center;
  margin-top: 32px;
  padding-top: 24px;
  border-top: 1px solid #2a2a2a;
}

.supabase-signup-text {
  color: #9ca3af;
  font-size: 14px;
  font-weight: 400;
}

.supabase-signup-link {
  background: none;
  border: none;
  color: #3ecf8e;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: color 0.2s ease;
  padding: 0;
  margin-left: 4px;
}

.supabase-signup-link:hover {
  color: #2dd4bf;
}
</style>