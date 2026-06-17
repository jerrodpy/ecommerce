import { ref } from 'vue'
import { ordersService } from '../../services/admin/orders.js'

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

            const response = await ordersService.getAll(params)

            orders.value = response.data?.items ?? []
        } catch (err) {
            error.value = err.message
            console.error('Помилка завантаження замовлень:', err)
        } finally {
            loading.value = false
        }
    }

    const updateOrderStatus = async (orderId, statusData) => {
        try {
            const response = await ordersService.update(orderId, statusData)
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
            await ordersService.update(orderId, { comments: comment })
            const order = orders.value.find(o => o.id === orderId)
            if (order) {
                order.comments = comment
            }
        } catch (err) {
            error.value = err.message
            throw err
        }
    }

    const deleteOrder = async (orderId) => {
        try {
            await ordersService.delete(orderId)
            orders.value = orders.value.filter(o => o.id !== orderId)
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
        deleteOrder,
    }
}
