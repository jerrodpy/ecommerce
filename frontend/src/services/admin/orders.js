import { api } from '../api.js';

export const ordersService = {
  getAll: (params) => api.get('/admin/orders', params),
  update: (id, data) => api.put(`/admin/orders/${id}`, data),
  delete: (id) => api.delete(`/admin/orders/${id}`),
};
