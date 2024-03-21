<script setup>
	import { LangDataHandler } from './../ts/LangDataHandler';
    import langsData from './locales/SideBar.json';

    const langData = LangDataHandler.initLangDataHandler("SideBar", langsData).langData;
</script>

<template>
    <div v-if="loading" class="loader">
		<div class="loader__spinner"></div>
		<div class="loader__text">{{ langData["text"] }}</div>
    </div>
</template>
  
<style lang="scss" scoped src="./scss/Loader.scss"></style>