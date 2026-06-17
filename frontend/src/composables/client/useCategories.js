import { ref } from 'vue';
import { clientService } from '../../services/client.js';

const categories = ref([]);
const loading = ref(false);
const error = ref(null);

export function useCategories() {
  const fetchCategories = async () => {
    loading.value = true;
    error.value = null;

    try {
      const response = await clientService.categories();
      categories.value = response.data?.items ?? [];
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };

  return { categories, loading, error, fetchCategories };
}
