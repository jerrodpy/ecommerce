import {api} from  './api.js'

export const clientService = {
    categories: () => api.get('/categories'),
    products: (params) => api.get('/products', params),
    orders: () => api.get('/orders'),

    addToCarts: (data) => api.post('/carts', data),
    addProductToCarts: (data, cart) => api.put('/carts/' + cart + '/add' , data),
    updateProductInCarts: (data, cart, product) => api.put('/carts/'+ cart + '/products/' + product, data),
    deleteProductFromCarts: (data, cart, product) => api.delete('/carts/'+ cart + '/products/' + product, data),
}