<template>
  <div class="card">
    <div class="card-header text-white" :class="isEdit ? 'bg-primary' : 'bg-success'">
      <h5 class="mb-0">
        <i class="bi bi-box"></i> 
        {{ isEdit ? 'Редактирование товара' : 'Создание нового товара' }}
      </h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="$emit('submit', formData)">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label class="form-label fw-bold">
                Название товара <span class="text-danger">*</span>
              </label>
              <input 
                v-model="formData.title" 
                type="text" 
                class="form-control" 
                placeholder="Введите название"
                required
              >
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">
                Описание <span class="text-danger">*</span>
              </label>
              <textarea 
                v-model="formData.description" 
                class="form-control" 
                rows="5" 
                placeholder="Подробное описание товара..."
                required
              ></textarea>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">
                Цена <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <input 
                  v-model="formData.price" 
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
              <label class="form-label fw-bold">Изображение</label>
              <div 
                @click="$refs.fileInput.click()"
                class="upload-area rounded"
              >
                <div v-if="!imagePreview" class="text-center">
                  <i class="bi bi-cloud-upload" style="font-size: 3rem;"></i>
                  <p class="mb-0">Кликните для загрузки</p>
                  <small>или перетащите файл</small>
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
              <label class="form-label fw-bold">Категории</label>
              <div class="card">
                <div class="card-body">
                  <div 
                    v-for="category in categories" 
                    :key="category.id"
                    class="form-check mb-2"
                  >
                    <input 
                      v-model="formData.categories"
                      :value="category.id"
                      class="form-check-input" 
                      type="checkbox" 
                      :id="`cat-form-${category.id}`"
                    >
                    <label class="form-check-label" :for="`cat-form-${category.id}`">
                      {{ category.name }}
                    </label>
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
            @click="$emit('cancel')" 
            class="btn btn-secondary"
          >
            <i class="bi bi-x-circle"></i> Отмена
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  product: {
    type: Object,
    default: null
  },
  categories: {
    type: Array,
    required: true
  },
  isEdit: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['submit', 'cancel'])

const fileInput = ref(null)
const imagePreview = ref(null)

const formData = ref({
  title: '',
  description: '',
  price: '',
  image: null,
  categories: []
})

// Загружаем данные товара при редактировании
watch(() => props.product, (newProduct) => {
  if (newProduct) {
    formData.value = {
      title: newProduct.title,
      description: newProduct.description,
      price: newProduct.price,
      image: newProduct.image,
      categories: [...(newProduct.categories || [])]
    }
    imagePreview.value = newProduct.image
  } else {
    resetForm()
  }
}, { immediate: true })

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
      formData.value.image = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const clearImage = () => {
  imagePreview.value = null
  formData.value.image = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const resetForm = () => {
  formData.value = {
    title: '',
    description: '',
    price: '',
    image: null,
    categories: []
  }
  imagePreview.value = null
}
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
</style>
