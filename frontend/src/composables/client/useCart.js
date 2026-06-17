import { ref, computed, reactive } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import { clientService } from '../../services/client.js'

const STORAGE_GUEST_KEY = 'guest_id'
const STORAGE_CART_KEY = 'cart_id'

let _guestId = localStorage.getItem(STORAGE_GUEST_KEY)
if (!_guestId) {
    _guestId = uuidv4()
    localStorage.setItem(STORAGE_GUEST_KEY, _guestId)
}

export const guestId = _guestId
export const cartId = ref(localStorage.getItem(STORAGE_CART_KEY) || null)
export const items = ref([])
export const removingIds = reactive(new Set())

function setCartFromResponse(data) {
    if (!data) return
    if (data.id) {
        cartId.value = data.id
        localStorage.setItem(STORAGE_CART_KEY, data.id)
    }
    if (Array.isArray(data.products)) {
        items.value = data.products.map(p => ({
            product_id: p.product_id,
            quantity: p.quantity,
            product: p.product || {},
        }))
    }
}

export function useCart() {
    const itemsCount = computed(() =>
        items.value.reduce((total, item) => total + item.quantity, 0)
    )

    const totalPrice = computed(() =>
        items.value.reduce((total, item) => {
            const price = item.product?.price ?? 0
            return total + price * item.quantity
        }, 0)
    )

    const initCart = async () => {
        try {
            const response = await clientService.getCart(guestId)
            if (response?.data) {
                setCartFromResponse(response.data)
            }
        } catch (err) {
            console.error('initCart error:', err)
        }
    }

    const addItem = async (product) => {
        const existingItem = items.value.find(item => item.product_id === product.id)

        if (existingItem) {
            await updateQuantity(product.id, existingItem.quantity + 1)
            return
        }

        items.value.push({
            product_id: product.id,
            quantity: 1,
            product: {
                id: product.id,
                title: product.title,
                price: product.price,
                image: product.image,
            },
        })

        try {
            const response = await clientService.addToCart({
                guest_id: guestId,
                products: [{ product_id: product.id, quantity: 1 }],
            })
            if (response?.data) {
                setCartFromResponse(response.data)
            }
        } catch (err) {
            const idx = items.value.findIndex(i => i.product_id === product.id)
            if (idx !== -1) items.value.splice(idx, 1)
            console.error('addItem error:', err)
        }
    }

    const updateQuantity = async (productId, qty) => {
        const item = items.value.find(i => i.product_id === productId)
        if (item) {
            item.quantity = qty
        }
        if (!cartId.value) return
        try {
            const response = await clientService.updateProductInCart(cartId.value, productId, { quantity: qty })
            if (response?.data) {
                setCartFromResponse(response.data)
            }
        } catch (err) {
            console.error('updateQuantity error:', err)
        }
    }

    const removeItem = async (productId) => {
        removingIds.add(productId)
        try {
            const response = await clientService.deleteProductFromCart(cartId.value, productId, guestId)
            if (response?.data) {
                setCartFromResponse(response.data)
            }
        } catch (err) {
            console.error('removeItem error:', err)
        } finally {
            removingIds.delete(productId)
        }
    }

    const clearCart = () => {
        items.value = []
        cartId.value = null
        localStorage.removeItem(STORAGE_CART_KEY)
    }

    return {
        guestId,
        cartId,
        items,
        removingIds,
        itemsCount,
        totalPrice,
        initCart,
        addItem,
        updateQuantity,
        removeItem,
        clearCart,
    }
}