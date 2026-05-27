import {ref, computed, readonly} from 'vue'
import {clientService} from "@/services/client.js";
import { v4 as uuidv4 } from 'uuid';

const STORAGE_GUEST_KEY = 'guest_id';
const STORAGE_CART_KEY = 'cart_id';
const STORAGE_CART_ITEMS_KEY = 'cart_items';
export const items = ref([])
const guestId = ref(null);
const cartId = ref(null);

// Загружаем из localStorage при инициализации
const loadFromLocalStorage = () => {
  const saved = localStorage.getItem(STORAGE_CART_ITEMS_KEY)
  if (saved) {
    items.value = JSON.parse(saved)
  }
}

export function useGuestId() {
    if (!guestId.value) {
        guestId.value = localStorage.getItem(STORAGE_GUEST_KEY) || uuidv4();
        localStorage.setItem(STORAGE_GUEST_KEY, guestId.value);
    }

    return {
        guestId: readonly(guestId)
    };
}

const saveToLocalStorage = () => {
  localStorage.setItem(STORAGE_CART_ITEMS_KEY, JSON.stringify(items.value))
}

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
  const addItem = async (product) => {
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

      useGuestId();

      if(cartId.value) {

      } else {
          const params = {
              guest_id: guestId.value,
              products: [
                  {
                      'product_id': product.id,
                      'quantity': 1,
                  }
              ],
          }

          console.log('PARAMS: ', params)

          try {
              const response = await clientService.addToCarts(params);
              cartId.value = response.data.id

              localStorage.setItem(STORAGE_CART_KEY, cartId.value);
              alert(response.message);
          } catch (err) {
              error.value = err.message;
          } finally {
              loading.value = false;
          }
      }


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
      const params = {
          quantity: quantity,
      }
      try {
          const data = clientService.updateProductInCarts(params, )
          // categories.value = data.data || data

          alert(data.message)
      } catch (err) {
          error.value = err.message
      } finally {
          loading.value = false
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
