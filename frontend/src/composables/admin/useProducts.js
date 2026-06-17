import { ref } from 'vue';
import { productsService } from '../../services/admin/products.js';

const products = ref([]);
const loading = ref(false);
const error = ref(null);

export function useProducts() {
  const fetchProducts = async () => {
    loading.value = true;
    error.value = null;

    try {
      const response = await productsService.getAll();
      products.value = response.data?.items ?? [];
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  };

  const buildFormData = (formData) => {
    const fd = new FormData();
    fd.append('title', formData.title ?? '');
    fd.append('description', formData.description ?? '');
    fd.append('price', formData.price ?? '');
    if (formData.image instanceof File) {
      fd.append('image', formData.image);
    }
    (formData.categories ?? []).forEach((id) => fd.append('categories[]', id));
    return fd;
  };

  const addProduct = async (formData) => {
    loading.value = true;
    error.value = null;

    try {
      const data = await productsService.create(buildFormData(formData));
      if (data.data) {
        products.value.push(data.data);
      } else {
        await fetchProducts();
      }
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateProduct = async (id, formData) => {
    loading.value = true;
    error.value = null;

    try {
      const payload = {
        title: formData.title,
        description: formData.description,
        price: formData.price,
        categories: formData.categories ?? [],
      };
      let data = await productsService.update(id, payload);

      if (formData.image instanceof File) {
        const fd = new FormData();
        fd.append('image', formData.image);
        data = await productsService.uploadImage(id, fd);
      }

      if (data.data) {
        const index = products.value.findIndex((p) => p.id === id);
        if (index !== -1) {
          products.value[index] = data.data;
        }
      } else {
        await fetchProducts();
      }
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const deleteProduct = async (id) => {
    loading.value = true;
    error.value = null;

    try {
      await productsService.delete(id);
      products.value = products.value.filter((p) => p.id !== id);
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  return {
    products,
    loading,
    error,
    fetchProducts,
    addProduct,
    updateProduct,
    deleteProduct,
  };
}
