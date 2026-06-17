<template>
  <div class="admin-layout">
    <nav class="navbar navbar-dark bg-warning mb-4">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-dark">
          <i class="bi bi-gear-fill"></i> Панель адміністратора
        </span>
        <button class="btn btn-outline-dark" @click="logout">
          <i class="bi bi-box-arrow-right"></i> Вихід
        </button>
      </div>
    </nav>

    <div class="container-fluid">
      <ul class="nav nav-pills mb-4">
        <li class="nav-item">
          <router-link to="/admin/categories" class="nav-link">
            <i class="bi bi-tag"></i> Категорії
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/admin/products" class="nav-link">
            <i class="bi bi-box"></i> Товари
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/admin/orders" class="nav-link">
            <i class="bi bi-clipboard-check"></i> Замовлення
          </router-link>
        </li>
      </ul>

      <main>
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from '../composables/useAuth.js';

const { logout } = useAuth();
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
