<template>
  <div class="home">
    <h1>Главная страница</h1>
    <button @click="goToAbout">Перейти на About</button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goToAbout = () => {
  router.push('/about')
}
</script>