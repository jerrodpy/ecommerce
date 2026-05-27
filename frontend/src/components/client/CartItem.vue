<template>
  <div class="card mb-3">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-2">
          <div class="cart-item-image">
            <img v-if="item.product && item.product.image" :src="item.product.image" :alt="item.product.title" class="w-100">
            <i v-else class="bi bi-image"></i>
          </div>
        </div>
        <div class="col-md-4">
          <h5>{{ item.product?.title }}</h5>
          <p class="text-muted mb-0">Цена: {{ item.product?.price ?? '' }} ₽</p>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center justify-content-end gap-2">
            <button 
              @click="decreaseQuantity" 
              class="btn btn-outline-secondary"
              :disabled="item.quantity <= 1"
            >
              <i class="bi bi-dash"></i>
            </button>
            <input 
              :value="item.quantity" 
              @input="updateQuantity($event.target.value)"
              type="number" 
              class="form-control text-center" 
              style="width: 70px;"
              min="1"
            >
            <button @click="increaseQuantity" class="btn btn-outline-secondary">
              <i class="bi bi-plus"></i>
            </button>
            <span class="fw-bold ms-3" style="min-width: 100px;">
              {{ (item.product && item.product.price ? item.product.price * item.quantity : 0) }} ₽
            </span>
            <button @click="$emit('remove', item.product_id)" class="btn btn-danger">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['update-quantity', 'remove'])

const updateQuantity = (value) => {
  const quantity = parseInt(value)
  if (quantity > 0) {
    emit('update-quantity', props.item.product_id, quantity)
  }
}

const increaseQuantity = () => {
  emit('update-quantity', props.item.product_id, props.item.quantity + 1)
}

const decreaseQuantity = () => {
  if (props.item.quantity > 1) {
    emit('update-quantity', props.item.product_id, props.item.quantity - 1)
  }
}
</script>

<style scoped>
.cart-item-image {
  background: #e9ecef;
  width: 100px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
  font-size: 2rem;
  overflow: hidden;
}

.cart-item-image img {
  object-fit: cover;
  height: 100%;
}
</style>
