import { ref, computed } from 'vue'
import { api } from '../services/api.js'
import router from '../router/index.js'

const user = ref(null)
const token = ref(null)
const loading = ref(false)
const error = ref(null)

// Load persisted state from localStorage on module init
const savedToken = localStorage.getItem('token')
const savedUser = localStorage.getItem('user')

if (savedToken) {
  token.value = savedToken
}

if (savedUser) {
  try {
    user.value = JSON.parse(savedUser)
  } catch {
    localStorage.removeItem('user')
  }
}

export function useAuth() {
  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  const login = async ({ email, password }) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/login', { email, password })
      const data = response.data

      token.value = data.access_token
      user.value = {
        id: data.id,
        name: data.name,
        email: data.email,
        role: data.role ?? null,
      }

      localStorage.setItem('token', data.access_token)
      localStorage.setItem('user', JSON.stringify(user.value))

      return { success: true }
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const register = async ({ name, email, password, passwordConfirm }) => {
    loading.value = true
    error.value = null

    try {
      await api.post('/register', { email, password })
      // After successful registration, log in to obtain an access token
      return await login({ email, password })
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const logout = () => {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/auth')
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    login,
    register,
    logout,
  }
}
