<template>
  <span class="badge status-badge" :class="badgeClass">
    <i v-if="showIcon" :class="iconClass" class="me-1"></i>
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: {
    type: String,
    required: true,
  },
  showIcon: {
    type: Boolean,
    default: false,
  },
});

const STATUS_MAP = {
  Pending: { label: 'Очікує', badge: 'bg-secondary text-white' },
  New: { label: 'Новий', badge: 'bg-warning text-dark' },
  Processing: { label: 'В обробці', badge: 'bg-info text-white' },
  Completed: { label: 'Виконано', badge: 'bg-success text-white' },
  Canceled: { label: 'Скасовано', badge: 'bg-danger text-white' },
};

const ICON_MAP = {
  New: 'bi bi-star-fill',
  Processing: 'bi bi-clock-fill',
  Completed: 'bi bi-check-circle-fill',
  Canceled: 'bi bi-x-circle-fill',
};

const label = computed(() => STATUS_MAP[props.status]?.label ?? props.status);
const badgeClass = computed(() => STATUS_MAP[props.status]?.badge ?? 'bg-secondary text-white');
const iconClass = computed(() => ICON_MAP[props.status] ?? 'bi bi-circle-fill');
</script>

<style scoped>
.status-badge {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  font-weight: 500;
}
</style>
