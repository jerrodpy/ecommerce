<template>
  <div>
    <h2 class="mb-4">Управление заказами</h2>
    
    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Администратор может просматривать заказы, изменять их статус и добавлять комментарии
    </div>
    
    <!-- Фильтры по статусу -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-3">
            <label class="form-label fw-bold">Фильтр по статусу:</label>
            <select v-model="statusFilter" class="form-select">
              <option value="">Все заказы</option>
              <option value="Новый">Новый</option>
              <option value="В обработке">В обработке</option>
              <option value="Доставляется">Доставляется</option>
              <option value="Выполнен">Выполнен</option>
              <option value="Отменен">Отменен</option>
            </select>
            <!-- Выпадающий список со статусами -->
<!--            <select class="form-select">-->
<!--              <option value="">Выберите статус</option>-->
<!--              <option v-for="status in statuses" :key="status" :value="status">-->
<!--                {{ status }}-->
<!--              </option>-->
<!--            </select>-->
          </div>
          <div class="col-md-9 text-end">
            <div class="d-flex gap-2 justify-content-end">
              <span class="badge bg-secondary">Всего: {{ orders.length }}</span>
              <span class="badge bg-warning text-dark">Новых: {{ getOrdersByStatus('Новый').length }}</span>
              <span class="badge bg-info">В работе: {{ getOrdersByStatus('В обработке').length }}</span>
              <span class="badge bg-success">Выполнено: {{ getOrdersByStatus('Выполнен').length }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Список заказов -->
    <div class="card mb-4">
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
                <th>Сумма</th>
                <th style="width: 150px;">Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredOrders.length === 0">
                <td colspan="7" class="text-center text-muted py-4">
                  Заказов не найдено
                </td>
              </tr>
<!--              <tr -->
<!--                v-for="order in filteredOrders" -->
<!--                :key="order.id"-->
<!--                :class="{ 'table-warning': order.status === 'Новый' }"-->
<!--              >-->
<!--                <td><strong>#{{ order.id }}</strong></td>-->
<!--                <td>{{ order.customerFio }}</td>-->
<!--                <td>{{ order.customerPhone }}</td>-->
<!--                <td>-->
<!--                  <small>{{ formatDate(order.date) }}</small>-->
<!--                </td>-->
<!--                <td>-->
<!--                  <span -->
<!--                    class="badge status-badge"-->
<!--                    :class="getStatusClass(order.status)"-->
<!--                  >-->
<!--                    {{ order.status }}-->
<!--                  </span>-->
<!--                </td>-->
<!--                <td>-->
<!--                  <strong>{{ order.totalPrice }} ₽</strong>-->
<!--                </td>-->
<!--                <td>-->
<!--                  <button -->
<!--                    @click="viewOrderDetails(order)"-->
<!--                    class="btn btn-sm btn-info text-white"-->
<!--                  >-->
<!--                    <i class="bi bi-eye"></i> Детали-->
<!--                  </button>-->
<!--                </td>-->
<!--              </tr>-->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- Детальный вид заказа (Modal) -->
    <div v-if="selectedOrder" class="card">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-file-earmark-text"></i> Детали заказа #{{ selectedOrder.id }}
        </h5>
        <button 
          @click="selectedOrder = null"
          class="btn btn-sm btn-light"
        >
          <i class="bi bi-x"></i> Закрыть
        </button>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <h6 class="text-muted">Информация о клиенте</h6>
            <p class="mb-1">
              <strong>ФИО (customer_fio):</strong> {{ selectedOrder.customerFio }}
            </p>
            <p class="mb-1">
              <strong>Телефон (customer_phone):</strong> {{ selectedOrder.customerPhone }}
            </p>
            <p class="mb-1">
              <strong>Email:</strong> {{ selectedOrder.email || 'Не указан' }}
            </p>
            <p class="mb-1">
              <strong>Адрес:</strong> {{ selectedOrder.address }}
            </p>
            <p class="mb-1">
              <strong>Дата заказа:</strong> {{ formatDate(selectedOrder.date) }}
            </p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Управление заказом</h6>
            <label class="form-label fw-bold">Статус заказа (status)</label>
            <select 
              v-model="selectedOrder.status" 
              @change="updateOrderStatus"
              class="form-select mb-2"
            >
              <option>Новый</option>
              <option>В обработке</option>
              <option>Доставляется</option>
              <option>Выполнен</option>
              <option>Отменен</option>
            </select>
            <button 
              @click="saveOrderStatus" 
              class="btn btn-primary w-100"
            >
              <i class="bi bi-arrow-repeat"></i> Обновить статус
            </button>
          </div>
        </div>
        
        <h6 class="text-muted mb-3">Товары в заказе (products)</h6>
        <div class="table-responsive mb-4">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in selectedOrder.products" :key="product.id">
                <td>{{ product.title }}</td>
                <td>{{ product.price }} ₽</td>
                <td>{{ product.quantity }}</td>
                <td><strong>{{ product.price * product.quantity }} ₽</strong></td>
              </tr>
            </tbody>
            <tfoot class="table-secondary">
              <tr>
                <th colspan="3" class="text-end">Итого:</th>
                <th>{{ selectedOrder.totalPrice }} ₽</th>
              </tr>
            </tfoot>
          </table>
        </div>
        
        <h6 class="text-muted mb-3">Комментарии (comments)</h6>
        <div class="mb-3">
          <div 
            v-for="(comment, index) in selectedOrder.comments" 
            :key="index"
            class="comment-item mb-2"
          >
            <strong>{{ comment.author }} ({{ formatDate(comment.date) }}):</strong><br>
            {{ comment.text }}
          </div>
          <div v-if="!selectedOrder.comments || selectedOrder.comments.length === 0" class="text-muted">
            Комментариев пока нет
          </div>
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
import { getStatuses } from '@/services/api'

const ordersStore = useOrders()

const orders = computed(() => ordersStore.orders)
const selectedOrder = ref(null)
const statusFilter = ref('')
const newComment = ref('')
const statuses = ref([])

const filteredOrders = computed(() => {
  // console.log('|filteredOrders| statusFilter: ' . statusFilter.value);
  console.log('|filteredOrders|: ' . orders);
  if (!statusFilter.value) return orders.value
  return orders.value.filter(order => order.status === statusFilter.value)
})

const getOrdersByStatus = (status) => {
  console.log('|filteredOrders| orders: ' . orders);

  // Проверяем что orders - это массив
  if (!orders || !orders.value || !Array.isArray(orders.value)) {
    return []
  }

  // return orders.value.filter(order => order.status === status)
  return statuses
}

const getStatusClass = (status) => {
  const statusClasses = {
    'Новый': 'bg-warning text-dark',
    'В обработке': 'bg-info',
    'Доставляется': 'bg-primary',
    'Выполнен': 'bg-success',
    'Отменен': 'bg-danger'
  }
  return statusClasses[status] || 'bg-secondary'
}

const viewOrderDetails = (order) => {
  selectedOrder.value = { ...order }
  window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' })
}

const updateOrderStatus = () => {
  // Статус обновляется автоматически через v-model
}

const saveOrderStatus = async () => {
  try {
    await ordersStore.updateOrderStatus(selectedOrder.value.id, selectedOrder.value.status)
    alert('Статус заказа успешно обновлен!')
  } catch (error) {
    alert('Ошибка при обновлении статуса: ' + error.message)
  }
}

const addComment = async () => {
  if (!newComment.value.trim()) return
  
  try {
    const comment = {
      author: 'Администратор',
      date: new Date().toISOString(),
      text: newComment.value
    }
    
    await ordersStore.addComment(selectedOrder.value.id, comment)
    
    // Обновляем локальную копию
    if (!selectedOrder.value.comments) {
      selectedOrder.value.comments = []
    }
    selectedOrder.value.comments.push(comment)
    
    newComment.value = ''
    alert('Комментарий успешно добавлен!')
  } catch (error) {
    alert('Ошибка при добавлении комментария: ' + error.message)
  }
}

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

onMounted(() => {
  ordersStore.fetchOrders()
  ordersStore.fetchStatuses()
})

// Загружаем статусы при монтировании
onMounted(async () => {
  loading.value = true
  try {
    const data = await getStatuses()
    // Если API возвращает { data: [...] }

    statuses.value = data.data || data

    console.log('Статусы:', statuses.value)
  } catch (err) {
    error.value = err.message
    console.error('Ошибка:', err)
  } finally {
    loading.value = false
  }
})
// onMounted(async () => {
//   await fetchOrders()
//
//   // Отладочные логи
//   console.log('orders:', orders)
//   console.log('orders.value:', orders.value)
//   console.log('Is array?', Array.isArray(orders.value))
//   console.log('Length:', orders.value?.length)
// })
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
