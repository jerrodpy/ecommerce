<template>
  <div class="container">
    <h2 class="mb-4">Оформлення замовлення</h2>

    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div class="row">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Дані покупця</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="submitOrder">
              <div class="mb-3">
                <label class="form-label fw-bold"> ПІБ <span class="text-danger">*</span> </label>
                <input
                  v-model="formData.customer_fio"
                  type="text"
                  class="form-control"
                  placeholder="Іванов Іван Іванович"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">
                  Телефон <span class="text-danger">*</span>
                </label>
                <input
                  v-model="formData.customer_phone"
                  type="tel"
                  class="form-control"
                  placeholder="+38 (099) 123-45-67"
                  required
                />
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-cart-check"></i> Ваше замовлення</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <div
                v-for="item in cartItems"
                :key="item.product_id"
                class="d-flex justify-content-between mb-2"
              >
                <span>{{ item.product?.title }} × {{ item.quantity }}</span>
                <span>{{ (item.product?.price ?? 0) * item.quantity }} ₴</span>
              </div>
            </div>

            <hr />

            <div class="d-flex justify-content-between mb-4">
              <h4>Разом:</h4>
              <h4 class="text-success">{{ totalPrice }} ₴</h4>
            </div>

            <button
              @click="submitOrder"
              class="btn btn-success btn-lg w-100"
              :disabled="isSubmitting"
            >
              <i class="bi bi-check-circle"></i>
              {{ isSubmitting ? 'Відправка...' : 'Підтвердити замовлення' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useCart } from '../../composables/client/useCart.js';
import { clientService } from '../../services/client.js';

const router = useRouter();
const { items: cartItems, cartId, totalPrice, clearCart } = useCart();

const formData = ref({
  customer_fio: '',
  customer_phone: '',
});

const isSubmitting = ref(false);
const errorMessage = ref('');

const submitOrder = async () => {
  errorMessage.value = '';

  if (!formData.value.customer_fio.trim() || !formData.value.customer_phone.trim()) {
    errorMessage.value = "Будь ласка, заповніть усі обов'язкові поля (ПІБ та телефон)";
    return;
  }

  if (!cartId.value) {
    errorMessage.value = 'Кошик порожній або не знайдений. Додайте товари до кошика.';
    return;
  }

  isSubmitting.value = true;

  try {
    const orderData = {
      cart_id: cartId.value,
      customer_fio: formData.value.customer_fio.trim(),
      customer_phone: formData.value.customer_phone.trim(),
    };

    const response = await clientService.createOrder(orderData);

    if (response && response.success) {
      clearCart();
      router.push('/');
    } else {
      errorMessage.value = response?.message || 'Виникла помилка при оформленні замовлення';
    }
  } catch (error) {
    errorMessage.value = error.message || 'Виникла помилка при оформленні замовлення';
  } finally {
    isSubmitting.value = false;
  }
};
</script>
