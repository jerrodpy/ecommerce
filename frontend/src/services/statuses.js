// const API_URL = 'http://localhost:8000/api' // Ваш URL бэкенда
// const API_URL = import.meta.env.VITE_API_URL
//
// export async function getStatuses() {
//     const response = await fetch(`${API_URL}/admin/status`, {
//         method: 'GET',
//         headers: {
//             'Content-Type': 'application/json',
//             // Если нужен токен:
//             // 'Authorization': `Bearer ${localStorage.getItem('token')}`
//         }
//     })
//
//     if (!response.ok) {
//         throw new Error('Ошибка загрузки статусов')
//     }
//
//     return response.json()
// }

import { api } from './api'

export const statusesService = {
    // Получить все статусы
    getAll: () => api.get('/admin/status'),
}