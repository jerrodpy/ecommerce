import { ref } from 'vue'
import { categoriesService } from '../services/categories.js'

const categories = ref([])
const loading = ref(false)
const error = ref(null)

export function useCategories() {
  const fetchCategories = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await categoriesService.getAll()
      categories.value = response.data.items
    } catch (err) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const addCategory = async (data) => {
    loading.value = true
    error.value = null

    try {
      const response = await categoriesService.create({ title: data.title })
      categories.value.push(response.data)
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateCategory = async (id, data) => {
    loading.value = true
    error.value = null

    try {
      const response = await categoriesService.update(id, { title: data.title })
      const index = categories.value.findIndex(c => c.id === id)
      if (index !== -1) {
        categories.value[index] = response.data
      }
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteCategory = async (id) => {
    loading.value = true
    error.value = null

    try {
      await categoriesService.delete(id)
      categories.value = categories.value.filter(c => c.id !== id)
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    categories,
    loading,
    error,
    fetchCategories,
    addCategory,
    updateCategory,
    deleteCategory,
  }
}
