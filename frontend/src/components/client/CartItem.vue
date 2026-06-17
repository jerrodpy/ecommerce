<template>
  <div class="card mb-3 cart-item-wrapper" :class="{ 'is-removing': removing }">
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
          <p class="text-muted mb-0">Ціна: {{ item.product?.price ?? '' }} ₴</p>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center justify-content-end gap-2">
            <button
              @click="decreaseQuantity"
              class="btn btn-outline-secondary"
              :disabled="item.quantity <= 1 || removing"
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
              :disabled="removing"
            >
            <button @click="increaseQuantity" class="btn btn-outline-secondary" :disabled="removing">
              <i class="bi bi-plus"></i>
            </button>
            <span class="fw-bold ms-3" style="min-width: 100px;">
              {{ (item.product?.price ? item.product.price * item.quantity : 0) }} ₴
            </span>
            <button @click="$emit('remove', item.product_id)" class="btn btn-danger" :disabled="removing">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <div v-if="removing" class="removing-overlay">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">Видалення...</span>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  removing: {
    type: Boolean,
    default: false
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
.cart-item-wrapper {
  position: relative;
  transition: opacity 0.3s ease;
}

.cart-item-wrapper.is-removing {
  opacity: 0.5;
}

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

.removing-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: inherit;
  pointer-events: none;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
