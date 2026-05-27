import { ref, computed } from 'vue'

const user = ref(null)
const token = ref(null)
const loading = ref(false)
const error = ref(null)

// Загружаем из localStorage при инициализации
const loadFromLocalStorage = () => {
  const savedUser = localStorage.getItem('user')
  const savedToken = localStorage.getItem('token')

  if (savedUser && savedToken) {
    user.value = JSON.parse(savedUser)
    token.value = savedToken
  }
}

loadFromLocalStorage()

export function useAuth() {
  // Getters
  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  // Actions
  const login = async (credentials) => {
    loading.value = true
    error.value = null

    try {
      await new Promise(resolve => setTimeout(resolve, 500))

      const mockUser = {
        id: 1,
        email: credentials.email,
        name: 'Иван Иванов',
        role: credentials.email.includes('admin') ? 'admin' : 'user'
      }

      const mockToken = 'mock-jwt-token-' + Date.now()

      user.value = mockUser
      token.value = mockToken

      localStorage.setItem('user', JSON.stringify(mockUser))
      localStorage.setItem('token', mockToken)

      return mockUser
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const register = async (userData) => {
    loading.value = true
    error.value = null

    try {
      await new Promise(resolve => setTimeout(resolve, 500))

      const mockUser = {
        id: Date.now(),
        email: userData.email,
        name: userData.name,
        phone: userData.phone,
        role: 'user'
      }

      const mockToken = 'mock-jwt-token-' + Date.now()

      user.value = mockUser
      token.value = mockToken

      localStorage.setItem('user', JSON.stringify(mockUser))
      localStorage.setItem('token', mockToken)

      return mockUser
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('user')
    localStorage.removeItem('token')
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
    logout
  }
}
