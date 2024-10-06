<script setup lang="ts">
import { ref, watch, Ref, onMounted, onUnmounted } from 'vue';
import { useRoute, RouteLocationNormalizedLoaded } from 'vue-router';

import { PerfectScrollbar } from 'vue3-perfect-scrollbar';
import Header from './components/Header.vue';
import SideBar from './components/SideBar.vue';

import { container } from "jenesius-vue-modal";

import { ThemeHandler } from "./ts/handlers/ThemeHandler";
import { csrfTokenInput, getNewCsrfToken } from './ts/handlers/CSRFTokenHandler';

const isBurgerActive : Ref<boolean> = ref(false);

const windowWidth = ref(window.innerWidth);

const toggleBurger = () => 
{
    isBurgerActive.value = !isBurgerActive.value;
}

ThemeHandler.instance;

const route : RouteLocationNormalizedLoaded = useRoute();

const scroll : Ref<HTMLElement | null> = ref(null);

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

const onResize = () => 
{
    windowWidth.value = window.innerWidth;  
}

onMounted(() => 
{
    window.addEventListener('resize', onResize);
});

onUnmounted(() =>
{
    window.removeEventListener('resize', onResize);
});

getNewCsrfToken();
</script>

<template>
    <input type="hidden" id="csrfTokenInput" ref="csrfTokenInput">
    <Header @toggleBurger="toggleBurger" />
    <perfect-scrollbar v-if="windowWidth > 1050" ref="scroll">
        <router-view v-slot="{ Component }">
            <transition name="pageOpacity" mode="out-in">
                <component :is="Component" />
            </transition>
        </router-view>
    </perfect-scrollbar>
    <router-view v-else v-slot="{ Component }">
        <transition name="pageOpacity" mode="out-in">
            <component :is="Component" />
        </transition>
    </router-view>

    <container/>
    <SideBar :isBurgerActive="isBurgerActive" @toggleBurger="toggleBurger"/>
</template>