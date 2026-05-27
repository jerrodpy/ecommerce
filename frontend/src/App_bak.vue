<script setup>
import HelloWorld from './components/HelloWorld.vue'
import ItemProduct from "./components/ItemProduct.vue";
import {computed, ref, watch, provide} from 'vue';
import {onMounted} from 'vue';


defineEmits(['product-selected'])

provide('icon', '<||>')

let inputText = ref('');


// let userNameStatic = 'Maksim'
let userName = ref("Max");
// let userName = 'Maksim'
let users = ['ivan', 'vadim', 'oleg']
let h1 = ref();

console.log('- h1 - ', h1)


let isDarkTheme = ref(false);

let text = computed(() => {
  return isDarkTheme.value ? 'dark theme' : 'light theme'
});

// onMounted(() => {
//   console.log('- onMounted h1 - ', h1)
// });

const serverURL = 'https://yesno.wtf/api';
let data = ref(getData());

async function getData() {
  let response = await fetch(serverURL);
  console.log('RESPONSE: ' , response);

  data.value = await response.json()
}

let requestSent = ref(0);

watch(data, () => {
  requestSent.value++;
})

function changeName(){
  userName.value = userName.value.toUpperCase();
  console.log('- userName - ', userName);
  console.log('- event - ', event.target);
  event.target.disabled = true;
}

let carts = [{
  'albumId': 1,
  'id': 10,
  'title': '1',
  'url': '1',
  'thumbailUrl': '1',
}];

function addToCart(event) {
  console.log('- event.target -', event.target);

  event.target.disabled = true;
  cart.value.push(event.target.innerText)

}
</script>

<template>
  <router-view />

<!--  <p>-->
<!--    <button @click="getData"-->
<!--          class="btn">Check the answer</button>-->

<!--  </p>-->
<!--  <p>{{data.answer}}</p>-->
<!--  <p>You've made {{requestSent}}</p>-->

<!--  <button @click="event => $emit('product-selected', event)"
          class="btn">
    <slot></slot>
  </button>-->

<!--  <h1>Hello +++ </h1>-->
<!--  <p>{{userName}}</p>-->

<!--  <ItemProduct image="https://placeholder.co/600x400?test=Product"></ItemProduct>-->
<!--  <ItemProduct carts="{{carts}}"></ItemProduct>-->
<!--  <HelloWorld msg="Vite + Vue" />-->


  <!--  <ul class="m-3">
    <li class="display-6 py-2"
        v-for="user in users">
      {{user}}
    </li>
  </ul>-->
<!--
  <div class="text-center">

    <h2>Вводим текст</h2>
    <input class="form-control w50"
    v-model="inputText" type="text">

    <div> вводимый текст
    <br>
      <p>{{inputText}}</p>
    </div>
  </div>

  <p class="text-center my-3">
    <button @click="changeName"
            class="btn btn-pimary">Convert Name to upper case</button>
  </p>


  <Button v-for="i in 10">
    <span>Text</span>
    <template #icon>IMG</template>
&lt;!&ndash;    // именованый слот  (<slot name = "icon"></slot> )&ndash;&gt;
  </Button>-->


</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}


</style>
