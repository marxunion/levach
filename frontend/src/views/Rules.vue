<script setup lang="ts">
	import { computed } from 'vue';

	import { JsonData } from '../ts/helpers/JsonHelper';

	import { LangDataHandler, } from '../ts/handlers/LangDataHandler';
	import langsData from './locales/Rules.json';

	const langData = LangDataHandler.initLangDataHandler('Rules', langsData).langData;

	const sectionData = computed(() => 
	{
		const sections = (langData.value as JsonData)['sections'] as JsonData[];
 		return sections.map((section: JsonData) => 
		{
			const title : string = section['title'] as string;
			const description : string[] = section['description'] as string[];
			return { title, description }
		});
	});
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['title'] }}</h1>
		<div v-for="(section, index) in sectionData" :key="index" class="main__block">
			<p class="main__block__title" :key="section.title">{{ section.title }}</p>
			<p v-for="(desc, descIndex) in section.description" :key="descIndex" class="main__block__description">{{ desc }}</p>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/Rules.scss"></style>