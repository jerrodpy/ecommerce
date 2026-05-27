<template>
  <div>
    <h2 class="mb-4">Управление заказами</h2>

    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Администратор может просматривать заказы и изменять их статус
    </div>

    <!-- Фильтры по статусу -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-3">
            <label class="form-label fw-bold">Фильтр по статусу:</label>
            <select v-model="statusFilter" class="form-select">
              <option value="">Все заказы</option>
              <option v-for="s in STATUS_OPTIONS" :key="s.value" :value="s.value">
                {{ s.label }}
              </option>
            </select>
          </div>
          <div class="col-md-9 text-end">
            <div class="d-flex gap-2 justify-content-end flex-wrap">
              <span class="badge bg-secondary">Всего: {{ orders.length }}</span>
              <span class="badge bg-warning text-dark">New: {{ countByStatus('New') }}</span>
              <span class="badge bg-info">Processing: {{ countByStatus('Processing') }}</span>
              <span class="badge bg-success">Completed: {{ countByStatus('Completed') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border" role="status"></div>
    </div>

    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- Список заказов -->
    <div v-else class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Список заказов</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th style="width: 50px;">ID</th>
                <th>ФИО клиента</th>
                <th>Телефон</th>
                <th>Дата</th>
                <th>Статус</th>
                <th style="width: 150px;">Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredOrders.length === 0">
                <td colspan="6" class="text-center text-muted py-4">Заказов не найдено</td>
              </tr>
              <tr
                v-for="order in filteredOrders"
                :key="order.id"
                :class="{ 'table-warning': order.status === 'New' }"
              >
                <td><strong>#{{ order.id }}</strong></td>
                <td>{{ order.customer_fio }}</td>
                <td>{{ order.customer_phone }}</td>
                <td><small>{{ formatDate(order.created_at) }}</small></td>
                <td>
                  <span class="badge status-badge" :class="getStatusClass(order.status)">
                    {{ statusLabel(order.status) }}
                  </span>
                </td>
                <td>
                  <button
                    @click="viewOrderDetails(order)"
                    class="btn btn-sm btn-info text-white"
                  >
                    <i class="bi bi-eye"></i> Детали
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Детальный вид заказа -->
    <div v-if="selectedOrder" class="card">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-file-earmark-text"></i> Детали заказа #{{ selectedOrder.id }}
        </h5>
        <button @click="selectedOrder = null" class="btn btn-sm btn-light">
          <i class="bi bi-x"></i> Закрыть
        </button>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <h6 class="text-muted">Информация о клиенте</h6>
            <p class="mb-1"><strong>ФИО:</strong> {{ selectedOrder.customer_fio }}</p>
            <p class="mb-1"><strong>Телефон:</strong> {{ selectedOrder.customer_phone }}</p>
            <p class="mb-1"><strong>Дата заказа:</strong> {{ formatDate(selectedOrder.created_at) }}</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Управление заказом</h6>
            <label class="form-label fw-bold">Статус заказа</label>
            <select v-model="selectedStatus" class="form-select mb-2">
              <option v-for="s in STATUS_OPTIONS" :key="s.value" :value="s.value">
                {{ s.label }}
              </option>
            </select>
            <button @click="saveOrderStatus" class="btn btn-primary w-100">
              <i class="bi bi-arrow-repeat"></i> Обновить статус
            </button>
          </div>
        </div>

        <h6 class="text-muted mb-3">Товары в заказе</h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Сумма</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in selectedOrder.products" :key="product.id">
                <td>{{ product.title }}</td>
                <td>{{ product.price }} ₽</td>
                <td>{{ product.pivot?.quantity ?? product.quantity ?? 1 }}</td>
                <td><strong>{{ (product.price * (product.pivot?.quantity ?? product.quantity ?? 1)) }} ₽</strong></td>
              </tr>
              <tr v-if="!selectedOrder.products || selectedOrder.products.length === 0">
                <td colspan="4" class="text-center text-muted">Нет товаров</td>
              </tr>
            </tbody>
          </table>
        </div>

        <h6 class="text-muted mb-3">Комментарии</h6>
        <div class="mb-3">
          <div v-if="selectedOrder.comments" class="comment-item mb-2">
            {{ selectedOrder.comments }}
          </div>
          <div v-else class="text-muted">Комментариев пока нет</div>
        </div>

        <label class="form-label fw-bold">Добавить комментарий:</label>
        <textarea
          v-model="newComment"
          class="form-control mb-2"
          rows="3"
          placeholder="Введите комментарий к заказу..."
        ></textarea>
        <button
          @click="addComment"
          class="btn btn-success"
          :disabled="!newComment.trim()"
        >
          <i class="bi bi-chat-dots"></i> Добавить комментарий
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useOrders } from '@/composables/useOrders.js'

const STATUS_OPTIONS = [
  { value: 'Pending',    label: 'Ожидает',     num: 0 },
  { value: 'New',        label: 'Новый',        num: 1 },
  { value: 'Processing', label: 'В обработке',  num: 2 },
  { value: 'Completed',  label: 'Выполнен',     num: 3 },
  { value: 'Canceled',   label: 'Отменён',      num: 4 },
]

const ordersStore = useOrders()
const orders = ordersStore.orders
const loading = ordersStore.loading
const error = ordersStore.error

const selectedOrder = ref(null)
const selectedStatus = ref('')
const statusFilter = ref('')
const newComment = ref('')

const filteredOrders = computed(() => {
  if (!statusFilter.value) return orders.value
  return orders.value.filter(o => o.status === statusFilter.value)
})

const countByStatus = (status) => {
  if (!Array.isArray(orders.value)) return 0
  return orders.value.filter(o => o.status === status).length
}

const statusLabel = (value) => {
  return STATUS_OPTIONS.find(s => s.value === value)?.label ?? value
}

const getStatusClass = (status) => {
  const map = {
    'Pending':    'bg-secondary',
    'New':        'bg-warning text-dark',
    'Processing': 'bg-info',
    'Completed':  'bg-success',
    'Canceled':   'bg-danger',
  }
  return map[status] ?? 'bg-secondary'
}

const viewOrderDetails = (order) => {
  selectedOrder.value = { ...order }
  selectedStatus.value = order.status
  window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' })
}

const saveOrderStatus = async () => {
  const option = STATUS_OPTIONS.find(s => s.value === selectedStatus.value)
  if (!option) return

  try {
    await ordersStore.updateOrderStatus(selectedOrder.value.id, { status: option.num })
    selectedOrder.value.status = selectedStatus.value
    alert('Статус заказа успешно обновлён!')
  } catch (err) {
    alert('Ошибка при обновлении статуса: ' + err.message)
  }
}

const addComment = async () => {
  if (!newComment.value.trim()) return
  try {
    await ordersStore.addComment(selectedOrder.value.id, newComment.value.trim())
    selectedOrder.value.comments = newComment.value.trim()
    newComment.value = ''
    alert('Комментарий успешно добавлен!')
  } catch (err) {
    alert('Ошибка при добавлении комментария: ' + err.message)
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '—'
  return new Date(dateString).toLocaleString('ru-RU', {
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit'
  })
}

onMounted(() => {
  ordersStore.fetchOrders()
})
</script>

<style scoped>
.status-badge {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
}

.comment-item {
  background: #f8f9fa;
  border-left: 3px solid #0d6efd;
  padding: 1rem;
  border-radius: 4px;
}

.table-warning {
  background-color: rgba(255, 193, 7, 0.1);
}
</style>
