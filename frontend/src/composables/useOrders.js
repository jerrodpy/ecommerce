import { ref } from 'vue'
import { api } from '../services/api.js'

const orders = ref([])
const loading = ref(false)
const error = ref(null)

export function useOrders() {
  const fetchOrders = async (filters = {}) => {
    loading.value = true
    error.value = null

    try {
      const params = {}
      if (filters.status !== undefined && filters.status !== '') {
        params.status = filters.status
      }

      const response = await api.get('/admin/orders', params)

      // The endpoint uses paginate() which returns { data: { items: [...], total, ... } }
      if (response.data && response.data.items !== undefined) {
        orders.value = response.data.items
      } else if (Array.isArray(response.data)) {
        orders.value = response.data
      } else {
        orders.value = []
      }
    } catch (err) {
      error.value = err.message
      console.error('Ошибка загрузки заказов:', err)
    } finally {
      loading.value = false
    }
  }

  const updateOrderStatus = async (orderId, statusData) => {
    try {
      const response = await api.put(`/admin/orders/${orderId}`, statusData)

      // Update local order in array
      const index = orders.value.findIndex(o => o.id === orderId)
      if (index !== -1 && response.data) {
        orders.value[index] = response.data
      }

      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  const addComment = async (orderId, comment) => {
    try {
      const order = orders.value.find(o => o.id === orderId)
      if (order) {
        if (!order.comments) {
          order.comments = []
        }
        order.comments.push(comment)
      }
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  const deleteOrder = async (orderId) => {
    try {
      const index = orders.value.findIndex(o => o.id === orderId)
      if (index !== -1) {
        orders.value.splice(index, 1)
      }
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  return {
    orders,
    loading,
    error,
    fetchOrders,
    updateOrderStatus,
    addComment,
    deleteOrder
  }
}
