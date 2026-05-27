<template>
  <div>
    <h2 class="mb-4">Управление категориями</h2>
    
    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Здесь администратор может создавать новые категории и удалять существующие
    </div>
    
    <!-- Форма добавления -->
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Добавить новую категорию</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="addCategory">
          <div class="row">
            <div class="col-md-10">
              <label class="form-label fw-bold">Название категории</label>
              <input 
                v-model="newCategory.name" 
                type="text" 
                class="form-control" 
                placeholder="Например: Электроника"
                required
              >
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-plus-lg"></i> Создать
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Список категорий -->
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Существующие категории</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Количество товаров</th>
                <th>Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in categories" :key="category.id">
                <td>{{ category.id }}</td>
                <td>
                  <span v-if="editingId !== category.id">{{ category.name }}</span>
                  <input 
                    v-else 
                    v-model="editForm.name"
                    type="text" 
                    class="form-control form-control-sm"
                  >
                </td>
                <td>
                  <span class="badge bg-secondary">{{ category.productCount || 0 }}</span>
                </td>
                <td>
                  <template v-if="editingId !== category.id">
                    <button 
                      @click="startEdit(category)" 
                      class="btn btn-sm btn-primary"
                    >
                      <i class="bi bi-pencil"></i> Редактировать
                    </button>
                    <button 
                      @click="deleteCategory(category.id)" 
                      class="btn btn-sm btn-danger"
                    >
                      <i class="bi bi-trash"></i> Удалить
                    </button>
                  </template>
                  <template v-else>
                    <button @click="saveEdit" class="btn btn-sm btn-success">
                      <i class="bi bi-check"></i> Сохранить
                    </button>
                    <button @click="cancelEdit" class="btn btn-sm btn-secondary">
                      <i class="bi bi-x"></i> Отмена
                    </button>
                  </template>
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
import { useCategories } from '@/composables/useCategories.js'

const categoriesStore = useCategories()

const categories = computed(() => categoriesStore.categories)

const newCategory = ref({
  name: ''
})

const editingId = ref(null)
const editForm = ref({
  name: ''
})

const addCategory = async () => {
  try {
    await categoriesStore.addCategory(newCategory.value)
    newCategory.value.name = ''
    alert('Категория успешно добавлена!')
  } catch (error) {
    alert('Ошибка при добавлении категории: ' + error.message)
  }
}

const startEdit = (category) => {
  editingId.value = category.id
  editForm.value.name = category.name
}

const saveEdit = async () => {
  try {
    await categoriesStore.updateCategory(editingId.value, editForm.value)
    editingId.value = null
    alert('Категория успешно обновлена!')
  } catch (error) {
    alert('Ошибка при обновлении категории: ' + error.message)
  }
}

const cancelEdit = () => {
  editingId.value = null
}

const deleteCategory = async (id) => {
  if (confirm('Вы уверены, что хотите удалить эту категорию?')) {
    try {
      await categoriesStore.deleteCategory(id)
      alert('Категория успешно удалена!')
    } catch (error) {
      alert('Ошибка при удалении категории: ' + error.message)
    }
  }
}

onMounted(() => {
  categoriesStore.fetchCategories()

  console.log('CATS: ' , categories.value);
})
</script>
