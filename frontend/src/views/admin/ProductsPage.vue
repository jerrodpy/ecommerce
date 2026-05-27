<template>
  <div>
    <h2 class="mb-4">Управление товарами</h2>
    
    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Администратор может добавлять, редактировать и удалять товары с загрузкой изображений и выбором категорий
    </div>
    
    <button 
      @click="showProductForm = true; editingProduct = null" 
      class="btn btn-success btn-lg mb-4"
    >
      <i class="bi bi-plus-circle"></i> Добавить новый товар
    </button>
    
    <!-- Форма создания/редактирования товара -->
    <div v-if="showProductForm" class="card mb-4">
      <div class="card-header text-white" :class="editingProduct ? 'bg-primary' : 'bg-success'">
        <h5 class="mb-0">
          <i class="bi bi-box"></i> 
          {{ editingProduct ? 'Редактирование товара' : 'Создание нового товара' }}
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="saveProduct">
          <div class="row">
            <div class="col-md-8">
              <div class="mb-3">
                <label class="form-label fw-bold">
                  Название товара (title) <span class="text-danger">*</span>
                </label>
                <input 
                  v-model="productForm.title" 
                  type="text" 
                  class="form-control" 
                  placeholder="Введите название"
                  required
                >
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">
                  Описание (description) <span class="text-danger">*</span>
                </label>
                <textarea 
                  v-model="productForm.description" 
                  class="form-control" 
                  rows="5" 
                  placeholder="Подробное описание товара..."
                  required
                ></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">
                  Цена (price) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <input 
                    v-model="productForm.price" 
                    type="number" 
                    class="form-control" 
                    placeholder="0.00" 
                    step="0.01"
                    required
                  >
                  <span class="input-group-text">₽</span>
                </div>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label fw-bold">Изображение (image)</label>
                <div 
                  @click="$refs.fileInput.click()"
                  class="upload-area rounded"
                >
                  <div v-if="!imagePreview" class="text-center">
                    <i class="bi bi-cloud-upload" style="font-size: 3rem;"></i>
                    <p class="mb-0">Кликните для загрузки</p>
                    <small>или перетащите файл сюда</small>
                  </div>
                  <img 
                    v-else 
                    :src="imagePreview" 
                    alt="Preview" 
                    class="img-fluid rounded"
                  >
                </div>
                <input 
                  ref="fileInput"
                  type="file" 
                  class="d-none"
                  accept="image/*"
                  @change="handleImageUpload"
                >
                <button 
                  v-if="imagePreview"
                  @click.prevent="clearImage"
                  type="button"
                  class="btn btn-sm btn-outline-danger mt-2 w-100"
                >
                  <i class="bi bi-trash"></i> Удалить изображение
                </button>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Категории (categories)</label>
                <div class="card">
                  <div class="card-body">
                    <div 
                      v-for="category in categories" 
                      :key="category.id"
                      class="form-check mb-2"
                    >
                      <input 
                        v-model="productForm.categories"
                        :value="category.id"
                        class="form-check-input" 
                        type="checkbox" 
                        :id="`cat-${category.id}`"
                      >
                      <label class="form-check-label" :for="`cat-${category.id}`">
                        {{ category.name }}
                      </label>
                    </div>
                    <div v-if="categories.length === 0" class="text-muted small">
                      Нет доступных категорий
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Сохранить
            </button>
            <button 
              type="button"
              @click="cancelEdit" 
              class="btn btn-secondary"
            >
              <i class="bi bi-x-circle"></i> Отмена
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Список товаров -->
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list"></i> Список товаров</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th style="width: 50px;">ID</th>
                <th style="width: 100px;">Изображение</th>
                <th>Название</th>
                <th style="width: 120px;">Цена</th>
                <th>Категории</th>
                <th style="width: 200px;">Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="products.length === 0">
                <td colspan="6" class="text-center text-muted py-4">
                  Нет товаров
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
                  <span class="badge bg-success">{{ product.price }} ₽</span>
                </td>
                <td>
                  <span 
                    v-for="catId in product.categories" 
                    :key="catId"
                    class="badge bg-info me-1"
                  >
                    {{ getCategoryName(catId) }}
                  </span>
                  <span v-if="!product.categories || product.categories.length === 0" class="text-muted small">
                    Без категории
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
import { ref, computed, onMounted } from 'vue'
import { useProducts } from '@/composables/useProducts.js'
import { useCategories } from '@/composables/useCategories.js'

const productsStore = useProducts()
const categoriesStore = useCategories()

const products = computed(() => productsStore.products)
const categories = computed(() => categoriesStore.categories)

const showProductForm = ref(false)
const editingProduct = ref(null)
const imagePreview = ref(null)
const fileInput = ref(null)

const productForm = ref({
  title: '',
  description: '',
  price: '',
  image: null,
  categories: []
})

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
      productForm.value.image = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const clearImage = () => {
  imagePreview.value = null
  productForm.value.image = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const editProduct = (product) => {
  editingProduct.value = product
  productForm.value = {
    title: product.title,
    description: product.description,
    price: product.price,
    image: product.image,
    categories: [...(product.categories || [])]
  }
  imagePreview.value = product.image
  showProductForm.value = true
  
  // Скролл к форме
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveProduct = async () => {
  try {
    if (editingProduct.value) {
      // Обновление существующего товара
      await productsStore.updateProduct(editingProduct.value.id, productForm.value)
      alert('Товар успешно обновлен!')
    } else {
      // Создание нового товара
      await productsStore.addProduct(productForm.value)
      alert('Товар успешно добавлен!')
    }
    
    cancelEdit()
  } catch (error) {
    alert('Ошибка при сохранении товара: ' + error.message)
  }
}

const cancelEdit = () => {
  showProductForm.value = false
  editingProduct.value = null
  productForm.value = {
    title: '',
    description: '',
    price: '',
    image: null,
    categories: []
  }
  imagePreview.value = null
}

const deleteProduct = async (id) => {
  if (confirm('Вы уверены, что хотите удалить этот товар?')) {
    try {
      await productsStore.deleteProduct(id)
      alert('Товар успешно удален!')
    } catch (error) {
      alert('Ошибка при удалении товара: ' + error.message)
    }
  }
}

const getCategoryName = (categoryId) => {
  const category = categories.value.find(c => c.id === categoryId)
  return category ? category.name : 'Неизвестная'
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
.upload-area {
  background: #e9ecef;
  min-height: 200px;
  border: 2px dashed #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #6c757d;
  transition: all 0.3s;
  padding: 10px;
}

.upload-area:hover {
  background: #dee2e6;
  border-color: #495057;
}

.upload-area img {
  max-height: 200px;
  object-fit: contain;
}

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
