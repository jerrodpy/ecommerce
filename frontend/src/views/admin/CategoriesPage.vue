<template>
  <div>
    <h2 class="mb-4">Управління категоріями</h2>

    <div class="alert alert-info">
      <i class="bi bi-info-circle"></i> Тут адміністратор може створювати нові категорії та видаляти
      існуючі
    </div>

    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Додати нову категорію</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="addCategory">
          <div class="row">
            <div class="col-md-10">
              <label class="form-label fw-bold">Назва категорії</label>
              <input
                v-model="newCategory.title"
                type="text"
                class="form-control"
                placeholder="Наприклад: Електроніка"
                required
              />
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-plus-lg"></i> Створити
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Існуючі категорії</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Кількість товарів</th>
                <th>Дії</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in categories" :key="category.id">
                <td>{{ category.id }}</td>
                <td>
                  <span v-if="editingId !== category.id">{{ category.title }}</span>
                  <input
                    v-else
                    v-model="editForm.title"
                    type="text"
                    class="form-control form-control-sm"
                  />
                </td>
                <td>
                  <span class="badge bg-secondary">{{ category.products_count ?? 0 }}</span>
                </td>
                <td>
                  <template v-if="editingId !== category.id">
                    <button @click="startEdit(category)" class="btn btn-sm btn-primary">
                      <i class="bi bi-pencil"></i> Редагувати
                    </button>
                    <button @click="deleteCategory(category.id)" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i> Видалити
                    </button>
                  </template>
                  <template v-else>
                    <button @click="saveEdit" class="btn btn-sm btn-success">
                      <i class="bi bi-check"></i> Зберегти
                    </button>
                    <button @click="cancelEdit" class="btn btn-sm btn-secondary">
                      <i class="bi bi-x"></i> Скасувати
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
import { ref, onMounted } from 'vue';
import { useCategories } from '../../composables/admin/useCategories.js';
import { useToast } from '../../composables/useToast.js';
import { useConfirm } from '../../composables/useConfirm.js';

const categoriesStore = useCategories();
const categories = categoriesStore.categories;
const toast = useToast();
const { confirm } = useConfirm();

const newCategory = ref({ title: '' });
const editingId = ref(null);
const editForm = ref({ title: '' });

const addCategory = async () => {
  try {
    await categoriesStore.addCategory(newCategory.value);
    newCategory.value.title = '';
    toast.success('Категорію успішно додано!');
  } catch (error) {
    toast.error('Помилка при додаванні категорії: ' + error.message);
  }
};

const startEdit = (category) => {
  editingId.value = category.id;
  editForm.value.title = category.title;
};

const saveEdit = async () => {
  try {
    await categoriesStore.updateCategory(editingId.value, editForm.value);
    editingId.value = null;
    toast.success('Категорію успішно оновлено!');
  } catch (error) {
    toast.error('Помилка при оновленні категорії: ' + error.message);
  }
};

const cancelEdit = () => {
  editingId.value = null;
};

const deleteCategory = async (id) => {
  if (!(await confirm('Ви впевнені, що хочете видалити цю категорію?'))) return;
  try {
    await categoriesStore.deleteCategory(id);
    toast.success('Категорію успішно видалено!');
  } catch (error) {
    toast.error('Помилка при видаленні категорії: ' + error.message);
  }
};

onMounted(() => {
  categoriesStore.fetchCategories();
});
</script>
