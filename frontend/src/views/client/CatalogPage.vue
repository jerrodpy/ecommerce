<template>
  <div class="container-fluid">
    <h2 class="mb-4">Каталог товаров</h2>
    <div class="row">
      <!-- Фильтры -->
      <div class="col-md-3">
        <Filters
            v-model:category="filters.category"
            v-model:priceFrom="filters.priceFrom"
            v-model:priceTo="filters.priceTo"
            @apply="applyFilters"
            @reset="resetFilters"
        />
      </div>

      <!-- Список товаров -->
      <div class="col-md-9">

          <div v-if="products.length > 0"
               class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="product in products" :key="product.id" class="col">
              <ProductCard
                  :product="product"
                  @add-to-cart="addToCart"
              />
            </div>
          </div>

          <div v-else class="alert alert-info">
                <i class="bi bi-info-circle"></i> Товары не найдены
          </div>


      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCategories } from '@/composables/useCategories.js'
import { useProducts } from '@/composables/useProducts.js'
import ProductCard from '@/components/client/ProductCard.vue'
import Filters from '@/components/client/Filters.vue'
import { useCart } from '@/composables/useCart.js'

const { products, fetchProducts, getFilteredProducts } = useProducts()
const { addItem } = useCart()
const { categories, loading, error, fetchCategories } = useCategories()
const selectedCategory = ref('')

const filters = ref({
  category: '',
  priceFrom: null,
  priceTo: null
})


const applyFilters = async () => {
  await fetchProducts(filters.value)
}

const resetFilters = async () => {
  filters.value = {
    category: '',
    priceFrom: null,
    priceTo: null
  }
  await fetchProducts(filters.value)
}

const addToCart = (product) => {
  console.log('addToCart ...' , product)
  addItem(product)  // ← ИЗМЕНЕНО
}

onMounted(async () => {
  await fetchProducts()
  await fetchCategories()

  console.log('Categories:', categories.value)
  console.log('products::', products.value)
})

</script>

<style scoped>


</style>