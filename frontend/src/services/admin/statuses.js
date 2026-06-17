import { api } from '../api.js';

export const statusesService = {
  getAll: () => api.get('/admin/status'),
};
