<template>
  <Teleport to="body">
    <div
      v-if="state.visible"
      class="modal d-block"
      style="background: rgba(0,0,0,0.5); z-index: 1200"
    >
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center py-4">
            <i class="bi bi-exclamation-triangle text-warning display-6 mb-3 d-block"></i>
            <p class="mb-0">{{ state.message }}</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button @click="reject" class="btn btn-secondary">Скасувати</button>
            <button @click="accept" class="btn btn-danger">Підтвердити</button>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { useConfirm } from '../../composables/useConfirm.js'
const { state, accept, reject } = useConfirm()
</script>