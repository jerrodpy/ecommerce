<script setup>
import { onMounted } from 'vue'
import { useCart } from './composables/client/useCart.js'
import ToastNotifications from './components/common/ToastNotifications.vue'
import ConfirmDialog from './components/common/ConfirmDialog.vue'

const { initCart } = useCart()
onMounted(() => initCart())
</script>

<template>
  <router-view />
  <ToastNotifications />
  <ConfirmDialog />
</template>

<style scoped>
</style>
