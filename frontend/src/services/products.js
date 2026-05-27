import { api } from './api'

export const productsService = {
    // Получить все статусы
    getAll: () => api.get('/admin/products'),

    // getById: (id) => api.get(`/products/${id}`),
    create: (data) => api.post('/products', data),
    update: (id, data) => api.put(`/products/${id}`, data),
    delete: (id) => api.delete(`/products/${id}`),

    // С фильтрами
    getFiltered: (filters) => {
        const params = new URLSearchParams(filters).toString()
        return api.get(`/products?${params}`)
    },
}
