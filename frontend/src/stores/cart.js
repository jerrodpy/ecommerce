import { ref, computed } from 'vue'

// Создаём глобальное состояние (будет одно на всё приложение)
const items = ref([])

// Загружаем из localStorage при инициализации
const loadFromLocalStorage = () => {
    const saved = localStorage.getItem('cart')
    if (saved) {
        items.value = JSON.parse(saved)
    }
}

const saveToLocalStorage = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
}

// Загружаем сразу
loadFromLocalStorage()

export function useCart() {
    // Getters
    const itemsCount = computed(() => {
        return items.value.reduce((total, item) => total + item.quantity, 0)
    })

    const totalItems = computed(() => {
        return items.value.reduce((total, item) => total + item.quantity, 0)
    })

    const totalPrice = computed(() => {
        return items.value.reduce((total, item) => total + (item.price * item.quantity), 0)
    })

    // Actions
    const addItem = (product) => {
        const existingItem = items.value.find(item => item.id === product.id)

        if (existingItem) {
            existingItem.quantity++
        } else {
            items.value.push({
                ...product,
                quantity: 1
            })
        }

        saveToLocalStorage()
    }

    const removeItem = (itemId) => {
        const index = items.value.findIndex(item => item.id === itemId)
        if (index !== -1) {
            items.value.splice(index, 1)
            saveToLocalStorage()
        }
    }

    const updateQuantity = (itemId, quantity) => {
        const item = items.value.find(item => item.id === itemId)
        if (item) {
            item.quantity = quantity
            saveToLocalStorage()
        }
    }

    const clearCart = () => {
        items.value = []
        saveToLocalStorage()
    }

    return {
        items,
        itemsCount,
        totalItems,
        totalPrice,
        addItem,
        removeItem,
        updateQuantity,
        clearCart
    }
}