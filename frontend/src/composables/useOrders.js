import { ref } from 'vue'
// import {resolver} from "vue-router/auto-resolver";

const orders = ref([])
const loading = ref(false)
const error = ref(null)
// let statuses = []

export function useOrders() {
  const fetchOrders = async () => {
    loading.value = true
    error.value = null

    try {
      await new Promise(resolve => setTimeout(resolve, 500))

      orders.value = [
        {
          id: 1,
          customerFio: 'Иванов Иван Иванович',
          customerPhone: '+7 (999) 123-45-67',
          email: 'ivanov@mail.com',
          address: 'г. Москва, ул. Ленина, д. 10, кв. 5',
          status: 'В обработке',
          totalPrice: 6497,
          date: '2025-10-26T10:30:00',
          products: [
            { id: 1, title: 'Товар 1', price: 1999, quantity: 2 },
            { id: 2, title: 'Товар 2', price: 2499, quantity: 1 }
          ],
          comments: [
            {
              author: 'Администратор',
              date: '2025-10-26T10:30:00',
              text: 'Заказ принят в обработку'
            },
            {
              author: 'Администратор',
              date: '2025-10-26T14:15:00',
              text: 'Товар упакован, ожидает отправки'
            }
          ]
        },
        {
          id: 2,
          customerFio: 'Петрова Мария Сергеевна',
          customerPhone: '+7 (999) 765-43-21',
          email: 'petrova@mail.com',
          address: 'г. Санкт-Петербург, пр. Невский, д. 25, кв. 12',
          status: 'Доставляется',
          totalPrice: 3250,
          date: '2025-10-25T15:20:00',
          products: [
            { id: 3, title: 'Товар 3', price: 3250, quantity: 1 }
          ],
          comments: [
            {
              author: 'Администратор',
              date: '2025-10-25T16:00:00',
              text: 'Заказ передан в службу доставки'
            }
          ]
        },
        {
          id: 3,
          customerFio: 'Сидоров Петр Петрович',
          customerPhone: '+7 (999) 111-22-33',
          email: 'sidorov@mail.com',
          address: 'г. Казань, ул. Баумана, д. 5, кв. 33',
          status: 'Выполнен',
          totalPrice: 12800,
          date: '2025-10-20T11:45:00',
          products: [
            { id: 4, title: 'Товар 4', price: 5500, quantity: 1 },
            { id: 5, title: 'Товар 5', price: 7200, quantity: 1 }
          ],
          comments: [
            {
              author: 'Администратор',
              date: '2025-10-20T12:00:00',
              text: 'Заказ доставлен и оплачен'
            }
          ]
        },
        {
          id: 4,
          customerFio: 'Козлова Анна Владимировна',
          customerPhone: '+7 (999) 555-66-77',
          email: 'kozlova@mail.com',
          address: 'г. Екатеринбург, ул. Малышева, д. 50, кв. 8',
          status: 'Новый',
          totalPrice: 4800,
          date: '2025-10-27T09:15:00',
          products: [
            { id: 6, title: 'Товар 6', price: 4800, quantity: 1 }
          ],
          comments: []
        },
        {
          id: 5,
          customerFio: 'Новиков Дмитрий Алексеевич',
          customerPhone: '+7 (999) 888-99-00',
          email: 'novikov@mail.com',
          address: 'г. Новосибирск, ул. Ленина, д. 100, кв. 45',
          status: 'Новый',
          totalPrice: 15998,
          date: '2025-10-27T08:30:00',
          products: [
            { id: 1, title: 'Товар 1', price: 1999, quantity: 3 },
            { id: 2, title: 'Товар 2', price: 2499, quantity: 4 }
          ],
          comments: []
        }
      ]
    } catch (err) {
      error.value = err.message
      console.error('Ошибка загрузки заказов:', err)
    } finally {
      loading.value = false
    }
  }
  // const fetchStatuses = async () => {
  //   loading.value = true
  //   error.value = null
  //   // statuses = []
  //
  //   try {
  //     // await new Promise(resolve => setTimeout(resolve, 500))
  //
  //       let response = await fetch('admin/status');
  //       console.log('RESPONSE: ' , response);
  //
  //       statuses = await response.json()
  //   } catch (err) {
  //     error.value = err.message
  //     console.error('Ошибка загрузки заказов:', err)
  //   } finally {
  //     loading.value = false
  //   }
  // }

    // let data = ref(getData());
    //
    // async function getData() {
    //     let response = await fetch('admin/status');
    //     console.log('RESPONSE: ' , response);
    //
    //     data.value = await response.json()
    // }

  const createOrder = async (orderData) => {
    try {
      const newOrder = {
        id: Date.now(),
        ...orderData,
        date: new Date().toISOString(),
        status: 'Новый',
        comments: []
      }

      orders.value.unshift(newOrder) // Добавляем в начало списка
      return newOrder
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  const updateOrderStatus = async (orderId, newStatus) => {
    try {
      const order = orders.value.find(o => o.id === orderId)
      if (order) {
        order.status = newStatus
      }
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  const addComment = async (orderId, comment) => {
    try {
      const order = orders.value.find(o => o.id === orderId)
      if (order) {
        if (!order.comments) {
          order.comments = []
        }
        order.comments.push(comment)
      }
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  const deleteOrder = async (orderId) => {
    try {
      const index = orders.value.findIndex(o => o.id === orderId)
      if (index !== -1) {
        orders.value.splice(index, 1)
      }
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  return {
    orders,
    loading,
    error,
    fetchOrders,
    createOrder,
    updateOrderStatus,
    addComment,
    deleteOrder
  }
}
