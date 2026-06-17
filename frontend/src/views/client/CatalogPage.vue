<template>
  <div class="container-fluid">
    <h2 class="mb-4">Каталог товарів</h2>
    <div class="row">
      <div class="col-md-3">
        <Filters
          :categories="categories"
          v-model:category="filters.category"
          v-model:priceFrom="filters.priceFrom"
          v-model:priceTo="filters.priceTo"
          @apply="applyFilters"
          @reset="resetFilters"
        />
      </div>

      <div class="col-md-9">
        <div v-if="products.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <div v-for="product in products" :key="product.id" class="col">
            <ProductCard
              :product="product"
              @add-to-cart="addToCart"
            />
          </div>
        </div>

        <div v-else class="alert alert-info">
          <i class="bi bi-info-circle"></i> Товари не знайдено
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProducts } from '../../composables/client/useProducts.js'
import { useCategories } from '../../composables/client/useCategories.js'
import ProductCard from '../../components/client/ProductCard.vue'
import Filters from '../../components/client/Filters.vue'
import { useCart } from '../../composables/client/useCart.js'

const { products, fetchProducts } = useProducts()
const { categories, fetchCategories } = useCategories()
const { addItem } = useCart()

const filters = ref({
  category: '',
  priceFrom: null,
  priceTo: null
})

const applyFilters = async () => {
  await fetchProducts(filters.value)
}

const resetFilters = async () => {
  filters.value = { category: '', priceFrom: null, priceTo: null }
  await fetchProducts(filters.value)
}

const addToCart = (product) => {
  addItem(product)
}

onMounted(() => {
  fetchProducts()
  fetchCategories()
})
</script>
