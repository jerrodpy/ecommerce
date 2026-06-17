<template>
  <div class="container">
    <h2 class="mb-4">Вхід / Реєстрація</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Вхід</h5>
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

              <button type="submit" class="btn btn-primary w-100 mb-2">Увійти</button>
              <a href="#" class="d-block text-center">Забули пароль?</a>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Реєстрація</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="register">
              <div class="mb-3">
                <label class="form-label fw-bold">ПІБ</label>
                <input
                  v-model="registerForm.name"
                  type="text"
                  class="form-control"
                  placeholder="Іванов Іван Іванович"
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
                  placeholder="+38 (099) 123-45-67"
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
                <label class="form-label fw-bold">Підтвердження пароля</label>
                <input
                  v-model="registerForm.passwordConfirm"
                  type="password"
                  class="form-control"
                  placeholder="********"
                  required
                >
              </div>

              <button type="submit" class="btn btn-success w-100">Зареєструватися</button>
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
import { useAuth } from '../../composables/useAuth.js'
import { useToast } from '../../composables/useToast.js'

const router = useRouter()
const authStore = useAuth()
const toast = useToast()

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
    toast.success('Вхід виконано успішно!')
    router.push('/')
  } catch (error) {
    toast.error('Помилка входу: ' + error.message)
  }
}

const register = async () => {
  if (registerForm.value.password !== registerForm.value.passwordConfirm) {
    toast.error('Паролі не збігаються!')
    return
  }

  try {
    await authStore.register(registerForm.value)
    toast.success('Реєстрація успішна!')
    router.push('/')
  } catch (error) {
    toast.error('Помилка реєстрації: ' + error.message)
  }
}
</script>
