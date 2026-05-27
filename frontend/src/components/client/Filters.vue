<template>
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-funnel"></i> Фильтры</h5>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label fw-bold">Категория:</label>
        <select 
          :value="category" 
          @input="$emit('update:category', $event.target.value)"
          class="form-select"
        >
          <option value="">Все категории</option>
          <option 
            v-for="cat in categories" 
            :key="cat.id" 
            :value="cat.id"
          >
            {{ cat.title }}
          </option>
        </select>
      </div>
      
      <div class="mb-3">
        <label class="form-label fw-bold">Цена:</label>
        <input 
          :value="priceFrom" 
          @input="$emit('update:priceFrom', $event.target.value)"
          type="number" 
          class="form-control mb-2" 
          placeholder="От"
        >
        <input 
          :value="priceTo" 
          @input="$emit('update:priceTo', $event.target.value)"
          type="number" 
          class="form-control" 
          placeholder="До"
        >
      </div>
      
      <button @click="$emit('apply')" class="btn btn-primary w-100 mb-2">
        Применить
      </button>
      <button @click="$emit('reset')" class="btn btn-outline-secondary w-100">
        Сбросить
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCategories } from '@/composables/useCategories.js'

defineProps({
  category: String,
  priceFrom: [Number, String],
  priceTo: [Number, String]
})

defineEmits(['update:category', 'update:priceFrom', 'update:priceTo', 'apply', 'reset'])

const categoriesStore = useCategories()
const { categories, loading, error, fetchCategories } = useCategories()
// const categories = computed(() => categoriesStore.categories)
</script>
