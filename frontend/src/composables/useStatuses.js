import { ref } from 'vue'
import { statusesService } from '../services/statuses.js'

const statuses = ref([])
const loading = ref(false)
const error = ref(null)

export function useStatuses() {
    const fetchStatuses = async () => {
        loading.value = true
        error.value = null

        try {
            const data = await statusesService.getAll()
            statuses.value = data.data || data
        } catch (err) {
            error.value = err.message // Ошибка уже обработана в api.js!
        } finally {
            loading.value = false
        }
    }

    return { statuses, loading, error, fetchStatuses }
}