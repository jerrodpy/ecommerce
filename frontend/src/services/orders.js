import { api } from './api'

export const ordersService = {
    // Получить все заказы
    getAll: () => api.get('/admin/orders'),

    // Получить заказ по ID
    getById: (id) => api.get(`/admin/orders/${id}`),

    // Создать заказ
    create: (data) => api.post('/orders', data),

    // Обновить статус
    updateStatus: (id, status) => api.patch(`/admin/orders/${id}/status`, { status }),

    // Добавить комментарий
    addComment: (id, comment) => api.post(`/admin/orders/${id}/comments`, comment),

    // Удалить заказ
    delete: (id) => api.delete(`/admin/orders/${id}`),
}