<script setup lang="ts">
	import './scss/NotFound.scss'
	import { ref } from 'vue';

	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/NotFound.json";

	const langData = ref(LangDataHandler.initLangDataHandler("NotFound", langsData).langData);
</script>
<template>
	<div>
		<h1>{{ langData['title'] }}</h1>
	</div>
</template>