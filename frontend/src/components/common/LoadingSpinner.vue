<template>
  <div class="loading-spinner" :class="{ 'fullscreen': fullscreen }">
    <div class="spinner-border" :class="sizeClass" :style="{ color: color }" role="status">
      <span class="visually-hidden">Загрузка...</span>
    </div>
    <p v-if="text" class="mt-3 text-muted">{{ text }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  color: {
    type: String,
    default: '#0d6efd'
  },
  text: {
    type: String,
    default: ''
  },
  fullscreen: {
    type: Boolean,
    default: false
  }
})

const sizeClass = computed(() => {
  const sizes = {
    sm: 'spinner-border-sm',
    md: '',
    lg: 'spinner-border-lg'
  }
  return sizes[props.size]
})
</script>

<style scoped>
.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.loading-spinner.fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  z-index: 9999;
}

.spinner-border-lg {
  width: 3rem;
  height: 3rem;
  border-width: 0.4em;
}
</style>
