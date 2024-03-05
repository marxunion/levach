<script setup lang="ts">
import { ref } from 'vue';
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import Header from './components/Header.vue'
import SideBar from './components/SideBar.vue'

import { container } from "jenesius-vue-modal";


const isBurgerActive = ref(false);

const toggleBurger = () => 
{
	isBurgerActive.value = !isBurgerActive.value;
};

</script>

<template>
	
  	<Header @toggleBurger="toggleBurger" />
		<perfect-scrollbar>
			<router-view v-slot="{ Component }">
				
				<transition name="pageOpacity" mode="out-in">
					
					<component :is="Component" />
				</transition>
			</router-view>
		</perfect-scrollbar>
		<container />
  	<SideBar :isBurgerActive="isBurgerActive" @toggleBurger="toggleBurger"/>
</template>
