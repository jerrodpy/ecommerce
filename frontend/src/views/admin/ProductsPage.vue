<template>
  <div>
    <h2 class="mb-4">Управління товарами</h2>

    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Адміністратор може додавати, редагувати та видаляти товари із завантаженням зображень та вибором категорій
    </div>

    <button
      @click="startAddProduct"
      class="btn btn-success btn-lg mb-4"
    >
      <i class="bi bi-plus-circle"></i> Додати новий товар
    </button>

    <Teleport to="body">
      <div
        v-if="showProductForm"
        class="modal d-block"
        tabindex="-1"
        style="background: rgba(0,0,0,0.5);"
        @click.self="cancelEdit"
      >
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <ProductForm
              :product="editingProduct"
              :categories="categories"
              :is-edit="!!editingProduct"
              @submit="saveProduct"
              @cancel="cancelEdit"
            />
          </div>
        </div>
      </div>
    </Teleport>

    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list"></i> Список товарів</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th style="width: 50px;">ID</th>
                <th style="width: 100px;">Зображення</th>
                <th>Назва</th>
                <th style="width: 120px;">Ціна</th>
                <th>Категорії</th>
                <th style="width: 200px;">Дії</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="products.length === 0">
                <td colspan="6" class="text-center text-muted py-4">
                  Немає товарів
                </td>
              </tr>
              <tr v-for="product in products" :key="product.id">
                <td>{{ product.id }}</td>
                <td>
                  <div class="product-thumbnail">
                    <img
                      v-if="product.image"
                      :src="product.image"
                      :alt="product.title"
                      class="img-fluid rounded"
                    >
                    <i v-else class="bi bi-image"></i>
                  </div>
                </td>
                <td>
                  <strong>{{ product.title }}</strong>
                  <br>
                  <small class="text-muted">{{ truncate(product.description, 50) }}</small>
                </td>
                <td>
                  <span class="badge bg-success">{{ product.price }} ₴</span>
                </td>
                <td>
                  <span
                    v-for="cat in product.categories"
                    :key="cat.id"
                    class="badge bg-info me-1"
                  >
                    {{ cat.title }}
                  </span>
                  <span v-if="!product.categories || product.categories.length === 0" class="text-muted small">
                    Без категорії
                  </span>
                </td>
                <td>
                  <button
                    @click="editProduct(product)"
                    class="btn btn-sm btn-primary"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="btn btn-sm btn-danger"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProducts } from '../../composables/admin/useProducts.js'
import { useCategories } from '../../composables/admin/useCategories.js'
import ProductForm from '../../components/admin/ProductForm.vue'
import { useToast } from '../../composables/useToast.js'
import { useConfirm } from '../../composables/useConfirm.js'

const productsStore = useProducts()
const categoriesStore = useCategories()
const toast = useToast()
const { confirm } = useConfirm()

const products = productsStore.products
const categories = categoriesStore.categories

const showProductForm = ref(false)
const editingProduct = ref(null)

const editProduct = (product) => {
  editingProduct.value = product
  showProductForm.value = true
}

const saveProduct = async (formData) => {
  try {
    if (editingProduct.value) {
      await productsStore.updateProduct(editingProduct.value.id, formData)
      toast.success('Товар успішно оновлено!')
    } else {
      await productsStore.addProduct(formData)
      toast.success('Товар успішно додано!')
    }
    cancelEdit()
  } catch (error) {
    toast.error('Помилка при збереженні товару: ' + error.message)
  }
}

const startAddProduct = () => {
  cancelEdit()
  showProductForm.value = true
}

const cancelEdit = () => {
  showProductForm.value = false
  editingProduct.value = null
}

const deleteProduct = async (id) => {
  if (!await confirm('Ви впевнені, що хочете видалити цей товар?')) return
  try {
    await productsStore.deleteProduct(id)
    toast.success('Товар успішно видалено!')
  } catch (error) {
    toast.error('Помилка при видаленні товару: ' + error.message)
  }
}

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

onMounted(() => {
  productsStore.fetchProducts()
  categoriesStore.fetchCategories()
})
</script>

<style scoped>
.product-thumbnail {
  width: 60px;
  height: 60px;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  overflow: hidden;
}

.product-thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-thumbnail i {
  font-size: 1.5rem;
  color: #6c757d;
}
</style>
