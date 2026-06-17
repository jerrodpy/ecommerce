import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import AdminLayout from '../layouts/AdminLayout.vue'

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        children: [
            {
                path: '',
                name: 'catalog',
                component: () => import('../views/client/CatalogPage.vue')
            },
            {
                path: 'cart',
                name: 'Cart',
                component: () => import('../views/client/CartPage.vue')
            },
            {
                path: 'checkout',
                name: 'Checkout',
                component: () => import('../views/client/CheckoutPage.vue')
            },
            {
                path: 'auth',
                name: 'Auth',
                component: () => import('../views/client/AuthPage.vue')
            },
        ],
    },
    {
        path: '/admin',
        component: AdminLayout,
        children: [
            {
                path: 'categories',
                name: 'admin-categories',
                component: () => import('../views/admin/CategoriesPage.vue')
            },
            {
                path: 'products',
                name: 'admin-products',
                component: () => import('../views/admin/ProductsPage.vue')
            },
            {
                path: 'orders',
                name: 'admin-orders',
                component: () => import('../views/admin/OrdersPage.vue')
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})

export default router
