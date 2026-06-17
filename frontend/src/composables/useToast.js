import { reactive } from 'vue'

const toasts = reactive([])
let nextId = 0

function add(message, type, duration = 3500) {
    const id = ++nextId
    toasts.push({ id, message, type })
    setTimeout(() => remove(id), duration)
}

function remove(id) {
    const index = toasts.findIndex(t => t.id === id)
    if (index !== -1) toasts.splice(index, 1)
}

export function useToast() {
    return {
        toasts,
        success: (msg) => add(msg, 'success'),
        error:   (msg) => add(msg, 'danger'),
        info:    (msg) => add(msg, 'info'),
        remove,
    }
}