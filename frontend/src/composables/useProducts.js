import { ref } from 'vue'
import { clientService } from "@/services/client.js"
import { api } from "@/services/api.js"

const products = ref([])
const loading = ref(false)
const error = ref(null)

export function useProducts() {

    const buildParams = (filters) => {
        const params = {
            current_page: 1,
            per_page: 10,
        }

        const filtersObj = {}

        if (filters.category) {
            filtersObj.category_id = filters.category
        }

        if (filters.priceFrom) {
            filtersObj.price_min = filters.priceFrom
        }

        if (filters.priceTo) {
            filtersObj.price_max = filters.priceTo
        }

        if (Object.keys(filtersObj).length > 0) {
            params.filters = filtersObj
        }

        return params
    }

    const fetchProducts = async (filters = {}) => {
        loading.value = true
        error.value = null

        try {
            const data = await clientService.products(buildParams(filters))
            products.value = data.data?.items ?? data.data ?? []

        } catch (err) {
            error.value = err.message
        } finally {
            loading.value = false
        }
    }

    const fetchAdminProducts = async () => {
        loading.value = true
        error.value = null

        try {
            const data = await api.get('/admin/products')
            products.value = data.data?.items ?? data.data ?? []
        } catch (err) {
            error.value = err.message
        } finally {
            loading.value = false
        }
    }

    const addProduct = async (formData) => {
        loading.value = true
        error.value = null

        try {
            const data = await api.post('/admin/products', {
                title: formData.title,
                description: formData.description,
                price: formData.price,
                image: formData.image,
                categories: formData.categories,
            })
            const newProduct = data.data
            if (newProduct) {
                products.value.push(newProduct)
            } else {
                await fetchAdminProducts()
            }
        } catch (err) {
            error.value = err.message
            throw err
        } finally {
            loading.value = false
        }
    }

    const updateProduct = async (id, formData) => {
        loading.value = true
        error.value = null

        try {
            const data = await api.put(`/admin/products/${id}`, {
                title: formData.title,
                description: formData.description,
                price: formData.price,
                image: formData.image,
                categories: formData.categories,
            })
            const updatedProduct = data.data
            if (updatedProduct) {
                const index = products.value.findIndex(p => p.id === id)
                if (index !== -1) {
                    products.value[index] = updatedProduct
                }
            } else {
                await fetchAdminProducts()
            }
        } catch (err) {
            error.value = err.message
            throw err
        } finally {
            loading.value = false
        }
    }

    const deleteProduct = async (id) => {
        loading.value = true
        error.value = null

        try {
            await api.delete(`/admin/products/${id}`)
            products.value = products.value.filter(p => p.id !== id)
        } catch (err) {
            error.value = err.message
            throw err
        } finally {
            loading.value = false
        }
    }

    return {
        products,
        loading,
        error,
        fetchProducts,
        fetchAdminProducts,
        addProduct,
        updateProduct,
        deleteProduct,
    }
}