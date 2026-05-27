import { ref } from 'vue'
import {clientService} from "@/services/client.js";

const categories = ref([])
const loading = ref(false)
const error = ref(null)

export function useCategories() {
  const fetchCategories = async () => {
    loading.value = true
    error.value = null

      try {
          const data = await clientService.categories()
          categories.value = data.data.items || data.data || data
      } catch (err) {
          error.value = err.message
      } finally {
          loading.value = false
      }
  }

  return {
    categories,
    loading,
    error,
    fetchCategories,
  }
}
