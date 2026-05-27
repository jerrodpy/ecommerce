const API_URL = import.meta.env.VITE_API_URL
function buildQueryString(params) {

    if (!params || Object.keys(params).length === 0) {
        return ''
    }

    const flattenParams = (obj, prefix = '') => {
        const result = []

        for (const key in obj) {
            if (!obj.hasOwnProperty(key)) continue

            const value = obj[key]
            const fullKey = prefix ? `${prefix}[${key}]` : key

            if (value === null || value === undefined || value === '') {
                continue
            }

            if (typeof value === 'object' && !Array.isArray(value)) {
                result.push(...flattenParams(value, fullKey))
            } else {
                result.push([fullKey, value])
            }
        }

        return result
    }

    const flattened = flattenParams(params)
    const searchParams = new URLSearchParams(flattened)

    return `?${searchParams.toString()}`
}

async function request(endpoint, options = {}) {

    // localStorage.getItem(STORAGE_GUEST_KEY)
    const queryString = options.params ? buildQueryString(options.params) : ''
    const url = `${API_URL}${endpoint}${queryString}`
    const token = localStorage.getItem('token')
    const headers = {
        'Content-Type': 'application/json',
        ...options.headers,
    }

    if (token) {
        headers['Authorization'] = `Bearer ${token}`
    }

    try {
        const response = await fetch(url, {
            method: options.method || 'GET',
            headers,
            ...(options.body && {body: options.body}),
        })

        const data = await response.json()

        if (!response.ok) {
            handleError(response.status, data)
        }

        return data
    } catch (error) {
        console.error('Network error:', error)
        throw new Error('Ошибка соединения с сервером')
    }
}

function handleError(status, data) {
    const message = data.message || data.error || 'Произошла ошибка'

    switch (status) {
        case 400:
            throw new Error(`Неверный запрос: ${message}`)
        case 401:
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            window.location.href = '/auth'
            throw new Error('Требуется авторизация')
        case 403:
            throw new Error('Доступ запрещен')
        case 404:
            throw new Error('Ресурс не найден')
        case 422:
            throw new Error(`Ошибка валидации: ${message}`)
        case 500:
            throw new Error('Ошибка сервера')
        default:
            throw new Error(message)
    }
}

export const api = {
    get: (endpoint, params) => request(endpoint, {method: 'GET', params}),

    post: (endpoint, data) => request(endpoint, {
        method: 'POST',
        body: JSON.stringify(data),
    }),

    put: (endpoint, data) => request(endpoint, {
        method: 'PUT',
        body: JSON.stringify(data),
    }),

    patch: (endpoint, data) => request(endpoint, {
        method: 'PATCH',
        body: JSON.stringify(data),
    }),

    delete: (endpoint) => request(endpoint, {method: 'DELETE'}),
}