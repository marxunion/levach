<script setup lang="ts">
import { ref, watch } from 'vue';
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';
import Header from './components/Header.vue';
import SideBar from './components/SideBar.vue';
import { useRoute } from 'vue-router';

const isBurgerActive = ref(false);

const toggleBurger = () => {
    isBurgerActive.value = !isBurgerActive.value;
};

const route = useRoute();

const scroll = ref(null);

watch(() => route.path, () => 
{
	setTimeout(() => 
	{
		if(scroll.value)
		{
			(scroll.value as any).ps.element.scrollTop = 0;
		}
	}, 400);
});
</script>

<template>
    <Header @toggleBurger="toggleBurger" />
    <perfect-scrollbar ref="scroll">
        <router-view v-slot="{ Component }">
            <transition name="pageOpacity" mode="out-in">
                <component :is="Component" />
            </transition>
        </router-view>
    </perfect-scrollbar>
    <container />
    <SideBar :isBurgerActive="isBurgerActive" @toggleBurger="toggleBurger"/>
</template>
