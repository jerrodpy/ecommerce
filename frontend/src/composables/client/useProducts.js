import { ref } from 'vue';
import { clientService } from '../../services/client.js';

const products = ref([]);
const loading = ref(false);
const error = ref(null);

export function useProducts() {
  const buildParams = (filters) => {
    const params = {
      current_page: 1,
      per_page: 10,
    };

    const filtersObj = {};

    if (filters.category) {
      filtersObj.category_id = filters.category;
    }
    if (filters.priceFrom) {
      filtersObj.price_min = filters.priceFrom;
    }
    if (filters.priceTo) {
      filtersObj.price_max = filters.priceTo;
    }

    if (Object.keys(filtersObj).length > 0) {
      params.filters = filtersObj;
    }

    return params;
  };

  const fetchProducts = async (filters = {}) => {
    loading.value = true;
    error.value = null;

    try {
      const data = await clientService.products(buildParams(filters));
      products.value = data.data?.items ?? [];
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };

  return {
    products,
    loading,
    error,
    fetchProducts,
  };
}
