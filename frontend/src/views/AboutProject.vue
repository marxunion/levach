<script setup lang="ts">
	import { computed, ComputedRef } from 'vue';
	
	import { JsonData } from '../ts/interfaces/JsonData';

	import { LangDataHandler } from '../ts/handlers/LangDataHandler';
	import langsData from './locales/AboutProject.json';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler('AboutProject', langsData).langData;

	interface Section
	{
		title: string;
		description: string;
	}

	const sectionData : ComputedRef<Section[]> = computed(() => 
	{
		const sections : JsonData[] = (langData.value as JsonData)['sections'] as JsonData[];
 		return sections.map((section: JsonData) => 
		{
			const title : string = section['title'] as string;
			const description : string = section['description'] as string;
			return { title, description }
		});
	});
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['titleAboutProject'] }}</h1>
		<div class="main__block">
			<p class="main__block__description"><b>{{ langData['descriptionBoldAboutProject']  }}</b>{{ langData['descriptionAboutProject']  }}</p>
		</div>
	  	<h1 class="main__title titleFAQ">{{ langData['titleFAQ'] }}</h1>
		<div v-for="(section, index) in sectionData" :key="index" class="main__block">
		  	<p class="main__block__title" :key="section.title">{{ section.title }}</p>
			<p class="main__block__description" :key="section.description">{{ section.description }}</p>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/AboutProject.scss"></style>