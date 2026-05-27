<template>
  <div class="container">
    <h2 class="mb-4">Оформление заказа</h2>

    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div class="row">
      <!-- Форма данных -->
      <div class="col-md-7">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Данные покупателя</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="submitOrder">
              <div class="mb-3">
                <label class="form-label fw-bold">
                  ФИО <span class="text-danger">*</span>
                </label>
                <input
                  v-model="formData.customer_fio"
                  type="text"
                  class="form-control"
                  placeholder="Иванов Иван Иванович"
                  required
                >
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">
                  Телефон <span class="text-danger">*</span>
                </label>
                <input
                  v-model="formData.customer_phone"
                  type="tel"
                  class="form-control"
                  placeholder="+7 (999) 123-45-67"
                  required
                >
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Сводка заказа -->
      <div class="col-md-5">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-cart-check"></i> Ваш заказ</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <div
                v-for="item in cartItems"
                :key="item.product_id"
                class="d-flex justify-content-between mb-2"
              >
                <span>{{ item.product?.title }} × {{ item.quantity }}</span>
                <span>{{ (item.product?.price ?? 0) * item.quantity }} ₽</span>
              </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-4">
              <h4>Итого:</h4>
              <h4 class="text-success">{{ totalPrice }} ₽</h4>
            </div>

            <button
              @click="submitOrder"
              class="btn btn-success btn-lg w-100"
              :disabled="isSubmitting"
            >
              <i class="bi bi-check-circle"></i>
              {{ isSubmitting ? 'Отправка...' : 'Подтвердить заказ' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCart } from '@/composables/useCart.js'
import { clientService } from '@/services/client.js'

const router = useRouter()
const cartStore = useCart()

const cartItems = cartStore.items
const totalPrice = cartStore.totalPrice

const formData = ref({
  customer_fio: '',
  customer_phone: ''
})

const isSubmitting = ref(false)
const errorMessage = ref('')

const submitOrder = async () => {
  errorMessage.value = ''

  if (!formData.value.customer_fio.trim() || !formData.value.customer_phone.trim()) {
    errorMessage.value = 'Пожалуйста, заполните все обязательные поля (ФИО и телефон)'
    return
  }

  if (!cartStore.cartId.value) {
    errorMessage.value = 'Корзина пуста или не найдена. Добавьте товары в корзину.'
    return
  }

  isSubmitting.value = true

  try {
    const orderData = {
      cart_id: cartStore.cartId.value,
      customer_fio: formData.value.customer_fio.trim(),
      customer_phone: formData.value.customer_phone.trim()
    }

    const response = await clientService.createOrder(orderData)

    if (response && response.success) {
      cartStore.clearCart()
      router.push('/')
    } else {
      errorMessage.value = response?.message || 'Произошла ошибка при оформлении заказа'
    }
  } catch (error) {
    console.error('Ошибка при оформлении заказа:', error)
    errorMessage.value = error.message || 'Произошла ошибка при оформлении заказа'
  } finally {
    isSubmitting.value = false
  }
}
</script>
