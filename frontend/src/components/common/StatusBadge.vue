<template>
  <span 
    class="badge status-badge"
    :class="badgeClass"
  >
    <i v-if="showIcon" :class="iconClass" class="me-1"></i>
    {{ status }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true
  },
  showIcon: {
    type: Boolean,
    default: false
  }
})

const badgeClass = computed(() => {
  const classes = {
    'Новый': 'bg-warning text-dark',
    'В обработке': 'bg-info text-white',
    'Доставляется': 'bg-primary text-white',
    'Выполнен': 'bg-success text-white',
    'Отменен': 'bg-danger text-white',
    'Активный': 'bg-success text-white',
    'Неактивный': 'bg-secondary text-white'
  }
  return classes[props.status] || 'bg-secondary text-white'
})

const iconClass = computed(() => {
  const icons = {
    'Новый': 'bi bi-star-fill',
    'В обработке': 'bi bi-clock-fill',
    'Доставляется': 'bi bi-truck',
    'Выполнен': 'bi bi-check-circle-fill',
    'Отменен': 'bi bi-x-circle-fill'
  }
  return icons[props.status] || 'bi bi-circle-fill'
})
</script>

<style scoped>
.status-badge {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  font-weight: 500;
}
</style>
