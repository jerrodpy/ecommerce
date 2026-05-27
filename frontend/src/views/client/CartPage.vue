<template>
  <div class="container">
    <h2 class="mb-4">Корзина покупок</h2>
    <!-- Если корзина пустая -->
    <div v-if="items.length === 0" class="alert alert-warning">
      <i class="bi bi-cart-x"></i> Ваша корзина пуста.
      <router-link to="/">Перейти к покупкам</router-link>
    </div>

    <!-- Список товаров в корзине -->
    <div v-else>
      <CartItem
        v-for="item in items"
        :key="item.product_id"
        :item="item"
        @update-quantity="updateQuantity"
        @remove="removeItem"
      />

      <!-- Итого -->
      <div class="card bg-light mt-4">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h4 class="mb-1">Итого:</h4>
              <p class="text-muted mb-0">Товаров: {{ totalItems }} шт.</p>
            </div>
            <div class="col-md-6 text-end">
              <h2 class="text-success mb-3">{{ totalPrice }} ₽</h2>
              <router-link to="/checkout" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle"></i> Оформить заказ
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import CartItem from '../../components/client/CartItem.vue'
import { items, useCart } from '../../composables/useCart.js'

const cartStore = useCart()

// cartStore.totalItems
// const cartItems = computed(() => cartStore.items)
const totalItems = computed(() => cartStore.totalItems)
const totalPrice = computed(() => cartStore.totalPrice)

const updateQuantity = (itemId, quantity) => {
  cartStore.updateQuantity(itemId, quantity)
}

const removeItem = (itemId) => {
  cartStore.removeItem(itemId)
}
</script>
