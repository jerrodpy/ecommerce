<template>
  <div class="admin-layout">
    <!-- Admin Header -->
    <nav class="navbar navbar-dark bg-warning mb-4">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-dark">
          <i class="bi bi-gear-fill"></i> Панель администратора
        </span>
        <button class="btn btn-outline-dark" @click="logout">
          <i class="bi bi-box-arrow-right"></i> Выход
        </button>
      </div>
    </nav>

    <!-- Admin Navigation -->
    <div class="container-fluid">
      <ul class="nav nav-pills mb-4">
        <li class="nav-item">
          <router-link to="/admin/categories" class="nav-link">
            <i class="bi bi-tag"></i> Категории
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/admin/products" class="nav-link">
            <i class="bi bi-box"></i> Товары
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/admin/orders" class="nav-link">
            <i class="bi bi-clipboard-check"></i> Заказы
          </router-link>
        </li>
      </ul>

      <!-- Main Admin Content -->
      <main>
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth.js'

const router = useRouter()
const authStore = useAuth()

const logout = () => {
  authStore.logout()
  router.push('/auth')
}
</script>

<style scoped>
.nav-link {
  color: #495057;
}

.nav-link:hover {
  color: #212529;
}

.router-link-active {
  background-color: #0d6efd !important;
  color: white !important;
}
</style>
