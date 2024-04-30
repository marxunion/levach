<script setup lang="ts">
	import { ref } from "vue";

	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/NotFound.json";

	const langData = LangDataHandler.initLangDataHandler("NotFound", langsData).langData;
</script>

<template>
	<main class="main">
		<h1>{{ langData['title'] }}</h1>
	</main>
</template>

<style lang="scss" scoped src="./scss/NotFound.scss"></style>