<template>
  <div class="container">
    <h2 class="mb-4">Кошик замовлень</h2>

    <div v-if="items.length === 0" class="alert alert-warning">
      <i class="bi bi-cart-x"></i> Ваш кошик порожній.
      <router-link to="/">Перейти до каталогу</router-link>
    </div>

    <div v-else>
      <CartItem
        v-for="item in items"
        :key="item.product_id"
        :item="item"
        :removing="removingIds.has(item.product_id)"
        @update-quantity="updateQuantity"
        @remove="removeItem"
      />

      <div class="card bg-light mt-4">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h4 class="mb-1">Разом:</h4>
              <p class="text-muted mb-0">Товарів: {{ itemsCount }} шт.</p>
            </div>
            <div class="col-md-6 text-end">
              <h2 class="text-success mb-3">{{ totalPrice }} ₴</h2>
              <router-link to="/checkout" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle"></i> Оформити замовлення
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import CartItem from '../../components/client/CartItem.vue';
import { useCart } from '../../composables/client/useCart.js';

const { items, removingIds, itemsCount, totalPrice, updateQuantity, removeItem } = useCart();
</script>
