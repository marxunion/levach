<script setup lang="ts">
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import Header from './components/Header.vue'
import SideBar from './components/SideBar.vue'

</script>

<template>
  	<Header />
		<perfect-scrollbar>
			<router-view v-slot="{ Component }">
				<transition name="pageOpacity" mode="out-in">
					<component :is="Component" />
				</transition>
			</router-view>
		</perfect-scrollbar>
  	<SideBar />
</template>
