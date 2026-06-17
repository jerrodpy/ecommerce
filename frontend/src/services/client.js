import { api } from './api.js';

export const clientService = {
  categories: () => api.get('/categories'),
  products: (params) => api.get('/products', params),
  orders: () => api.get('/orders'),

  getCart: (guestId) => api.get('/carts', { guest_id: guestId }),
  addToCart: (data) => api.post('/carts/items', data),
  updateProductInCart: (cartId, productId, data) =>
    api.put('/carts/' + cartId + '/products/' + productId, data),
  deleteProductFromCart: (cartId, productId, guestId) =>
    api.delete(
      '/carts/' + cartId + '/products/' + productId + '?guest_id=' + encodeURIComponent(guestId),
    ),
  createOrder: (data) => api.post('/orders', data),
};
