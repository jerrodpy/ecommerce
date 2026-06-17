<template>
  <div class="empty-state text-center py-5">
    <i :class="icon" class="display-1 text-muted mb-3"></i>
    <h4 class="text-muted mb-3">{{ title }}</h4>
    <p class="text-muted mb-4">{{ description }}</p>
    <slot name="action">
      <button v-if="actionText" @click="$emit('action')" class="btn btn-primary">
        <i :class="actionIcon" class="me-2"></i>
        {{ actionText }}
      </button>
    </slot>
  </div>
</template>

<script setup>
defineProps({
  icon: {
    type: String,
    default: 'bi bi-inbox',
  },
  title: {
    type: String,
    default: 'Немає даних',
  },
  description: {
    type: String,
    default: 'Тут поки нічого немає',
  },
  actionText: {
    type: String,
    default: '',
  },
  actionIcon: {
    type: String,
    default: 'bi bi-plus-circle',
  },
});

defineEmits(['action']);
</script>

<style scoped>
.empty-state {
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.empty-state i {
  opacity: 0.5;
}
</style>
