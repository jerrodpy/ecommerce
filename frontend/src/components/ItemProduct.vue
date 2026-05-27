<script setup>
import { ref } from 'vue'

defineProps({
  carts: Array,
})

const count = ref(0)


// let carts = [{
//   'albumId': 1,
//   'id': 10,
//   'title': '1',
//   'url': '1',
//   'thumbailUrl': '1',
// }];
</script>

<template>
  <div v-for="cart in carts"
       class="cart"
       style="max-width: 18rem">
    <div class="card-header">Product # {{cart.id}}</div>
    <div class="card-body">
      <h5 class="card-title">{{cart.title}}</h5>
      <p class="card-text">
        <img :src="image"
        alt = ''>
      </p>
    </div>
  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
