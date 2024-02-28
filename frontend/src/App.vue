<script setup lang="ts">
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import Header from './components/Header.vue'
import SideBar from './components/SideBar.vue'
</script>

<template>
  <Header />
  <perfect-scrollbar>
    <router-view />
  </perfect-scrollbar>
  <SideBar />
</template>

<style scoped>
#app 
{
  width: 100%;
  height: 100%;
}
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
