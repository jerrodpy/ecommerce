<script setup>
import { onMounted } from 'vue'
import { useCart } from './composables/useCart.js'

const { initCart } = useCart()
onMounted(() => initCart())
</script>

<template>
  <router-view />
</template>

<style scoped>
</style>
