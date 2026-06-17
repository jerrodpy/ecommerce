import { api } from '../api.js';

export const productsService = {
  getAll: () => api.get('/admin/products'),
  create: (formData) => api.postForm('/admin/products', formData),
  update: (id, data) => api.patch(`/admin/products/${id}`, data),
  uploadImage: (id, formData) => api.postForm(`/admin/products/${id}/image`, formData),
  delete: (id) => api.delete(`/admin/products/${id}`),
};
