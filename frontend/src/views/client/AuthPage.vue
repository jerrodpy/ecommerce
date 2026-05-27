<template>
  <div class="container">
    <h2 class="mb-4">Вход / Регистрация</h2>
    
    <div class="row">
      <!-- Форма входа -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Вход</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="login">
              <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input 
                  v-model="loginForm.email" 
                  type="email" 
                  class="form-control" 
                  placeholder="example@mail.com"
                  required
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Пароль</label>
                <input 
                  v-model="loginForm.password" 
                  type="password" 
                  class="form-control" 
                  placeholder="********"
                  required
                >
              </div>
              
              <button type="submit" class="btn btn-primary w-100 mb-2">Войти</button>
              <a href="#" class="d-block text-center">Забыли пароль?</a>
            </form>
          </div>
        </div>
      </div>
      
      <!-- Форма регистрации -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Регистрация</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="register">
              <div class="mb-3">
                <label class="form-label fw-bold">ФИО</label>
                <input 
                  v-model="registerForm.name" 
                  type="text" 
                  class="form-control" 
                  placeholder="Иванов Иван Иванович"
                  required
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input 
                  v-model="registerForm.email" 
                  type="email" 
                  class="form-control" 
                  placeholder="example@mail.com"
                  required
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Телефон</label>
                <input 
                  v-model="registerForm.phone" 
                  type="tel" 
                  class="form-control" 
                  placeholder="+7 (999) 123-45-67"
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Пароль</label>
                <input 
                  v-model="registerForm.password" 
                  type="password" 
                  class="form-control" 
                  placeholder="********"
                  required
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Подтверждение пароля</label>
                <input 
                  v-model="registerForm.passwordConfirm" 
                  type="password" 
                  class="form-control" 
                  placeholder="********"
                  required
                >
              </div>
              
              <button type="submit" class="btn btn-success w-100">Зарегистрироваться</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth.js'

const router = useRouter()
const authStore = useAuth()

const loginForm = ref({
  email: '',
  password: ''
})

const registerForm = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  passwordConfirm: ''
})

const login = async () => {
  try {
    await authStore.login(loginForm.value)
    alert('Вход выполнен успешно!')
    router.push('/')
  } catch (error) {
    alert('Ошибка входа: ' + error.message)
  }
}

const register = async () => {
  if (registerForm.value.password !== registerForm.value.passwordConfirm) {
    alert('Пароли не совпадают!')
    return
  }

  try {
    await authStore.register(registerForm.value)
    alert('Регистрация успешна!')
    router.push('/')
  } catch (error) {
    alert('Ошибка регистрации: ' + error.message)
  }
}
</script>
