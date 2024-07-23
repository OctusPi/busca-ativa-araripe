<script setup>
import { onMounted, ref} from 'vue'
import { RouterView } from 'vue-router'
import style from '@/stores/theme';

import ModalDeleteComp from './components/ModalDeleteComp.vue';
import AlertComp from './components/AlertComp.vue';

const datalist = ref([])
const alert    = ref({show: false, data:{type:'success', msg: ''}})
const remove   = ref({})

onMounted(() => {
  const screen = document.getElementById('screen')
  if(screen){ screen.classList.add(style.theme)}
})

</script>

<template>
  
  <div id="load-wall" class="load-wall d-none">
    <img id="load-img" class="load-img" src="./assets/imgs/load.svg">
  </div>

  <ModalDeleteComp 
  :params="remove" 
  @callUpdate="(data) => { datalist = data}"
  @callAlert="(data) => { alert = data}" />

  <AlertComp :alert="alert" />

  <RouterView 
      :datalist = "datalist"
      @callAlert="(data) => { alert = data}" 
      @callRemove="(data) => { remove = data }" />
  

</template>
