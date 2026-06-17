<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="toast show align-items-center border-0"
      :class="`text-bg-${toast.type}`"
      role="alert"
    >
      <div class="d-flex">
        <div class="toast-body">{{ toast.message }}</div>
        <button
          @click="remove(toast.id)"
          type="button"
          class="btn-close btn-close-white me-2 m-auto"
        ></button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useToast } from '../../composables/useToast.js';
const { toasts, remove } = useToast();
</script>
