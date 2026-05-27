<template>
  <div class="card h-100">
    <div class="product-image">
      <img v-if="product.image" :src="product.image" :alt="product.title" class="w-100">
      <i v-else class="bi bi-image"></i>
    </div>
    <div class="card-body">
      <h5 class="card-title">{{ product.title }} 555</h5>
      <p class="card-text text-muted">{{ product.description }}</p>
      <p class="h4 text-success mb-3">{{ product.price }} $</p>
      <button @click="$emit('add-to-cart', product)" class="btn btn-success w-100">
        <i class="bi bi-cart-plus"></i> В корзину
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  }
})

defineEmits(['add-to-cart'])
</script>

<style scoped>
.product-image {
  background: #e9ecef;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
  font-size: 3rem;
  overflow: hidden;
}

.product-image img {
  object-fit: cover;
  height: 100%;
}
</style>
