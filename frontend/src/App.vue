<script lang="ts">
import Header from './components/Header.vue'

import { defineComponent } from 'vue';

export default defineComponent({
  name: 'App',
  components: {
    Header,
  }
});
</script>

<template>
  <Header />
  <router-view></router-view>
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
