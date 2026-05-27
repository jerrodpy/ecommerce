<template>
  <div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="bi bi-file-earmark-text"></i> Детали заказа #{{ order.id }}
      </h5>
      <button 
        @click="$emit('close')"
        class="btn btn-sm btn-light"
      >
        <i class="bi bi-x"></i> Закрыть
      </button>
    </div>
    <div class="card-body">
      <!-- Информация о клиенте и управление -->
      <div class="row mb-4">
        <div class="col-md-6">
          <h6 class="text-muted mb-3">
            <i class="bi bi-person"></i> Информация о клиенте
          </h6>
          <table class="table table-sm table-borderless">
            <tr>
              <td class="text-muted" style="width: 40%;">ФИО:</td>
              <td><strong>{{ order.customerFio }}</strong></td>
            </tr>
            <tr>
              <td class="text-muted">Телефон:</td>
              <td>{{ order.customerPhone }}</td>
            </tr>
            <tr>
              <td class="text-muted">Email:</td>
              <td>{{ order.email || 'Не указан' }}</td>
            </tr>
            <tr>
              <td class="text-muted">Адрес:</td>
              <td>{{ order.address }}</td>
            </tr>
            <tr>
              <td class="text-muted">Дата заказа:</td>
              <td>{{ formatDate(order.date) }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-3">
            <i class="bi bi-gear"></i> Управление заказом
          </h6>
          <label class="form-label fw-bold">Статус заказа</label>
          <select 
            :value="order.status"
            @change="$emit('update-status', $event.target.value)"
            class="form-select mb-2"
          >
            <option>Новый</option>
            <option>В обработке</option>
            <option>Доставляется</option>
            <option>Выполнен</option>
            <option>Отменен</option>
          </select>
          <button 
            @click="$emit('save-status')"
            class="btn btn-primary w-100"
          >
            <i class="bi bi-arrow-repeat"></i> Обновить статус
          </button>
        </div>
      </div>
      
      <!-- Товары в заказе -->
      <h6 class="text-muted mb-3">
        <i class="bi bi-cart"></i> Товары в заказе
      </h6>
      <div class="table-responsive mb-4">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Название</th>
              <th style="width: 15%;">Цена</th>
              <th style="width: 15%;">Количество</th>
              <th style="width: 15%;">Сумма</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in order.products" :key="product.id">
              <td>{{ product.title }}</td>
              <td>{{ product.price }} ₽</td>
              <td class="text-center">{{ product.quantity }}</td>
              <td><strong>{{ product.price * product.quantity }} ₽</strong></td>
            </tr>
          </tbody>
          <tfoot class="table-secondary">
            <tr>
              <th colspan="3" class="text-end">Итого:</th>
              <th>{{ order.totalPrice }} ₽</th>
            </tr>
          </tfoot>
        </table>
      </div>
      
      <!-- Комментарии -->
      <h6 class="text-muted mb-3">
        <i class="bi bi-chat-dots"></i> Комментарии к заказу
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
          <i class="bi bi-info-circle"></i> Комментариев пока нет
        </div>
      </div>
      
      <!-- Добавление комментария -->
      <div class="card bg-light">
        <div class="card-body">
          <label class="form-label fw-bold">Добавить комментарий:</label>
          <textarea 
            :value="newComment"
            @input="$emit('update:newComment', $event.target.value)"
            class="form-control mb-2" 
            rows="3" 
            placeholder="Введите комментарий к заказу..."
          ></textarea>
          <button 
            @click="$emit('add-comment')"
            class="btn btn-success"
            :disabled="!newComment || !newComment.trim()"
          >
            <i class="bi bi-send"></i> Добавить комментарий
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
  const date = new Date(dateString)
  return date.toLocaleString('ru-RU', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
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
