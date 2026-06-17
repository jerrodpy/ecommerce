<template>
  <div class="default-layout">
    <nav class="navbar navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand mb-0 h1">
          <i class="bi bi-shop"></i> E-commerce
        </router-link>
        <div class="d-flex gap-3 align-items-center">
          <router-link to="/cart" class="btn btn-outline-light">
            <i class="bi bi-cart3"></i> Кошик ({{ cartCount }})
          </router-link>
          <template v-if="isAuthenticated">
            <span class="text-light small">{{ user?.name || user?.email }}</span>
            <button @click="logout" class="btn btn-outline-warning">
              <i class="bi bi-box-arrow-right"></i> Вийти
            </button>
          </template>
          <router-link v-else to="/auth" class="btn btn-outline-light">
            <i class="bi bi-person"></i> Увійти
          </router-link>
        </div>
      </div>
    </nav>

    <main>
      <router-view />
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
      <div class="container">
        <p class="mb-0">© 2025.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useCart } from '../composables/client/useCart.js';
import { useAuth } from '../composables/useAuth.js';

const { itemsCount: cartCount } = useCart();

const { user, isAuthenticated, logout } = useAuth();
</script>

<style scoped>
.default-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}
</style>
