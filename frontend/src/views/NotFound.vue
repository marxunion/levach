<script setup lang="ts">
	import { ComputedRef } from "vue";

	import { JsonData } from "../ts/interfaces/JsonData";

	import { LangDataHandler } from "../ts/handlers/LangDataHandler";
	import langsData from "./locales/NotFound.json";

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("NotFound", langsData).langData;
</script>

<template>
	<main class="main">
		<h1>{{ langData['title'] }}</h1>
	</main>
</template>

<style lang="scss" scoped src="./scss/NotFound.scss"></style>