<template>
  <div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="bi bi-file-earmark-text"></i> Деталі замовлення #{{ order.id }}
      </h5>
      <button
        @click="$emit('close')"
        class="btn btn-sm btn-light"
      >
        <i class="bi bi-x"></i> Закрити
      </button>
    </div>
    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-6">
          <h6 class="text-muted mb-3">
            <i class="bi bi-person"></i> Інформація про клієнта
          </h6>
          <table class="table table-sm table-borderless">
            <tr>
              <td class="text-muted" style="width: 40%;">ПІБ:</td>
              <td><strong>{{ order.customerFio }}</strong></td>
            </tr>
            <tr>
              <td class="text-muted">Телефон:</td>
              <td>{{ order.customerPhone }}</td>
            </tr>
            <tr>
              <td class="text-muted">Email:</td>
              <td>{{ order.email || 'Не вказано' }}</td>
            </tr>
            <tr>
              <td class="text-muted">Адреса:</td>
              <td>{{ order.address }}</td>
            </tr>
            <tr>
              <td class="text-muted">Дата замовлення:</td>
              <td>{{ formatDate(order.date) }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-3">
            <i class="bi bi-gear"></i> Управління замовленням
          </h6>
          <label class="form-label fw-bold">Статус замовлення</label>
          <select
            :value="order.status"
            @change="$emit('update-status', $event.target.value)"
            class="form-select mb-2"
          >
            <option>Новий</option>
            <option>В обробці</option>
            <option>Доставляється</option>
            <option>Виконано</option>
            <option>Скасовано</option>
          </select>
          <button
            @click="$emit('save-status')"
            class="btn btn-primary w-100"
          >
            <i class="bi bi-arrow-repeat"></i> Оновити статус
          </button>
        </div>
      </div>

      <h6 class="text-muted mb-3">
        <i class="bi bi-cart"></i> Товари в замовленні
      </h6>
      <div class="table-responsive mb-4">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Назва</th>
              <th style="width: 15%;">Ціна</th>
              <th style="width: 15%;">Кількість</th>
              <th style="width: 15%;">Сума</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in order.products" :key="product.id">
              <td>{{ product.title }}</td>
              <td>{{ product.price }} ₴</td>
              <td class="text-center">{{ product.quantity }}</td>
              <td><strong>{{ product.price * product.quantity }} ₴</strong></td>
            </tr>
          </tbody>
          <tfoot class="table-secondary">
            <tr>
              <th colspan="3" class="text-end">Разом:</th>
              <th>{{ order.totalPrice }} ₴</th>
            </tr>
          </tfoot>
        </table>
      </div>

      <h6 class="text-muted mb-3">
        <i class="bi bi-chat-dots"></i> Коментарі до замовлення
      </h6>
      <div class="mb-3">
        <div
          v-for="(comment, index) in order.comments"
          :key="index"
          class="comment-item mb-2"
        >
          <div class="d-flex justify-content-between align-items-start">
            <strong>{{ comment.author }}</strong>
            <small class="text-muted">{{ formatDate(comment.date) }}</small>
          </div>
          <p class="mb-0 mt-1">{{ comment.text }}</p>
        </div>
        <div v-if="!order.comments || order.comments.length === 0" class="alert alert-light">
          <i class="bi bi-info-circle"></i> Коментарів ще немає
        </div>
      </div>

      <div class="card bg-light">
        <div class="card-body">
          <label class="form-label fw-bold">Додати коментар:</label>
          <textarea
            :value="newComment"
            @input="$emit('update:newComment', $event.target.value)"
            class="form-control mb-2"
            rows="3"
            placeholder="Введіть коментар до замовлення..."
          ></textarea>
          <button
            @click="$emit('add-comment')"
            class="btn btn-success"
            :disabled="!newComment || !newComment.trim()"
          >
            <i class="bi bi-send"></i> Додати коментар
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  order: {
    type: Object,
    required: true
  },
  newComment: {
    type: String,
    default: ''
  }
})

defineEmits(['close', 'update-status', 'save-status', 'add-comment', 'update:newComment'])

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString('uk-UA', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<style scoped>
.comment-item {
  background: #f8f9fa;
  border-left: 3px solid #0d6efd;
  padding: 0.75rem;
  border-radius: 4px;
}
</style>
