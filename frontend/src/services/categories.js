import {api} from  './api.js'

export const categoriesService = {
    getAll: () => api.get('/categories'),
    // getById: (id) => api.get(`/categories/${id}`),
    create: (data) => api.post('/admin/categories', data),
    update: (id, data) => api.put(`/admin/categories/${id}`, data),
    delete: (id) => api.delete(`/admin/categories/${id}`),
}