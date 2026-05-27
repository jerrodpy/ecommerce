<template>
  <div class="default-layout">
    <!-- Header -->
    <nav class="navbar navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand mb-0 h1">
          <i class="bi bi-shop"></i> E-commerce
        </router-link>
        <div class="d-flex gap-3">
          <router-link to="/cart" class="btn btn-outline-light">
            <i class="bi bi-cart3"></i> Корзина ({{ cartCount }})
          </router-link>
          <router-link to="/auth" class="btn btn-outline-light">
            <i class="bi bi-person"></i> Войти
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
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
import { computed } from 'vue'
import { useCart } from '@/composables/useCart.js'

const cartStore = useCart()
const cartCount = computed(() => cartStore.itemsCount)
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
